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
use Illuminate\Validation\Rule; // <-- 1. JANGAN LUPA TAMBAHKAN INI DI ATAS


class RestoOrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user(); // User yang sedang login

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
            'order_timeout_at' => now()->addMinutes(5), // Contoh timeout
        ]);

        // Simpan item-itemnya
        foreach ($request->items as $item) {
            $itemData = \App\Models\Item::find($item['item_id']);
            $order->items()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $itemData->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibuat',
            'order_id' => $order->id,
        ], 201);
    }

    public function restoOrders()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant->id;

        $orders = Order::with(['items.item.restaurant', 'user']) // Eager load restoran
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
            'driver_id' => 'required|exists:drivers,id', // ✅ ganti dari 'users' ke 'drivers'
        ]);

        $order = Order::findOrFail($id);

        // ✅ Cek status harus 'diproses' sebelum bisa pilih driver
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



        // Cek apakah user adalah pemilik restoran
        $restaurantId = Restaurant::where('owner_id', $user->id)->value('id');

        if (!$restaurantId) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant tidak ditemukan untuk user ini.',
            ], 403);
        }

        // Ambil semua driver milik restoran tersebut
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
