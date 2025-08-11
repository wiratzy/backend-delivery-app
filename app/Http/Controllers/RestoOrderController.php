<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class RestoOrderController extends Controller
{

    private function sendNewOrderWhatsAppNotification(string $ownerPhoneNumber, string $restaurantName, string $customerName, string $customerPhone,int $orderId): void
    {
        $targetPhoneNumber = preg_replace('/^0/', '62', $ownerPhoneNumber); // Ubah 08xxx menjadi 628xxx

        $message = "Halo *$restaurantName*,\n\nAda pesanan baru masuk!\n\n" .
            "Dari: *$customerName*\n" .
            "Telepon: *$customerPhone*\n\n" .
            "Mohon segera periksa aplikasi Anda untuk melihat detail pesanan dan melakukan konfirmasi. Terima kasih!";

        $fonnteApiUrl = env('FONNTE_API_URL', 'https://api.fonnte.com/send');
        $fonnteApiKey = env('FONNTE_API_KEY');

        if (empty($fonnteApiKey)) {
            \Log::warning("Fonnte API Key is not set. WhatsApp notification for new order #{$orderId} skipped.");
            return;
        }

        try {
            Http::asForm()->withHeaders([
                'Authorization' => $fonnteApiKey,
            ])->post($fonnteApiUrl, [
                        'target' => $targetPhoneNumber,
                        'message' => $message,
                        'countryCode' => '62', // Opsional, tapi baik untuk disertakan
                    ]);

            \Log::info("New order WhatsApp notification sent to {$targetPhoneNumber} for order #{$orderId}.");
        } catch (\Exception $e) {
            \Log::error("Exception sending new order WhatsApp notification to {$targetPhoneNumber}: " . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $user = $request->user(); // User yang sedang login (customer)

        // ... (Validasi Anda sudah benar, tidak perlu diubah)
        $request->validate([
            'payment_method' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        // Transaksi Database untuk memastikan semua proses berhasil atau tidak sama sekali
        DB::beginTransaction();
        try {
            // Buat order
            $order = Order::create([
                'user_id' => $user->id,
                'restaurant_id' => $request->restaurant_id,
                'payment_method' => $request->payment_method,
                'total_price' => $request->subtotal + $request->delivery_fee,
                'delivery_fee' => $request->delivery_fee,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'menunggu_konfirmasi',
                'order_timeout_at' => now()->addMinutes(5),
            ]);

            // Tambahkan item ke order
            foreach ($request->items as $item) {
                $itemData = \App\Models\Item::find($item['item_id']);
                $order->items()->create([
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $itemData->price,
                ]);
            }

            // --- BAGIAN BARU: KIRIM NOTIFIKASI WHATSAPP KE PEMILIK RESTORAN ---
            $restaurant = Restaurant::with('owner')->find($request->restaurant_id);

            if ($restaurant && $restaurant->owner && $restaurant->owner->phone) {
                $this->sendNewOrderWhatsAppNotification(
                    $restaurant->owner->phone,
                    $restaurant->name,
                    $user->name,
                    customerPhone: $user->phone,
                    orderId: $order->id
                );
            } else {
                \Log::warning("Tidak dapat mengirim notifikasi WA: data pemilik restoran atau nomor telepon tidak ditemukan untuk restaurant_id: " . $request->restaurant_id);
            }
            // --- AKHIR BAGIAN BARU ---

            DB::commit(); // Simpan semua perubahan jika tidak ada error

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'order_id' => $order->id,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            \Log::error("Checkout failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan.',
            ], 500);
        }
    }

    public function restoOrders()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant->id;

        $orders = Order::with(['items.item.restaurant', 'user'])
            ->where('restaurant_id', $restaurantId)
            ->latest()
            ->get();


        return response()->json([
            'success' => true,
            'message' => 'Daftar order restoran berhasil diambil',
            'orders' => $orders
        ]);
    }


    public function show($id)
    {
        $user = auth()->user();

        $order = Order::with(['items.item.restaurant', 'user'])
            ->where('restaurant_id', $user->restaurant->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau tidak milik restoran Anda.'
            ], 404);
        }

        // Format ulang data jika perlu (misalnya path gambar)
        $orderArray = $order->toArray();
        $orderArray['items'] = collect($order->items)->map(function ($orderItem) {
            $item = $orderItem->item;
            $itemArray = $item->toArray();
            $itemArray['image'] = url('storage/items/' . $item->image);
            $itemArray['restaurant'] = $item->restaurant->toArray(); // include restaurant info

            $orderItemArray = $orderItem->toArray();
            $orderItemArray['item'] = $itemArray;

            return $orderItemArray;
        });

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diambil.',
            'order' => $orderArray
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu_konfirmasi,diproses,diantar,berhasil,dibatalkan'
        ]);

        $order = Order::findOrFail($id);
        // Optional: Validasi role user yang boleh update (misal hanya resto)
        if ($request->user()->role !== 'owner') {
            return response()->json([
                'message' => 'Unauthorized Role User: ' . $request->user()->role,
            ], 403);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'order' => $order
        ]);
    }

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $order = Order::findOrFail($id);

        if ($order->status !== 'diproses') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan harus dikonfirmasi terlebih dahulu sebelum memilih driver.',
            ], 422);
        }

        $order->driver_id = $request->driver_id;
        $order->driver_confirmed_at = now();
        $order->status = 'diantar';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Driver berhasil ditetapkan dan status diubah ke diantar',
            'data' => $order
        ]);
    }



    public function getAvailableDrivers(Request $request)
    {
        $user = $request->user();



        $restaurantId = Restaurant::where('owner_id', $user->id)->value('id');

        if (!$restaurantId) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant tidak ditemukan untuk user ini.',
            ], 403);
        }

        $drivers = Driver::where('restaurant_id', $restaurantId)
            ->get();

        if ($drivers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada driver tersedia untuk restoran ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar driver berhasil diambil',
            'data' => $drivers
        ]);
    }

}
