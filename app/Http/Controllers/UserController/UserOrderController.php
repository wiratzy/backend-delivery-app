<?php

namespace App\Http\Controllers\UserController;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserOrderController extends Controller
{

    public function userOrders()
    {
        $orders = Order::with('items.item')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => "berhasil mendapatkan semua data order",
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        $order = Order::with([
            'items.item',
            'items.item.restaurant',
            'driver' // ⬅️ ini yang penting
        ])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();



        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order Berhasil Ditemukan',
            'data' => $order
        ]);
    }

 public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|string|in:menunggu_konfirmasi,diproses,diantar,berhasil,dibatalkan',
        'restaurant_rating' => 'nullable|integer|min:1|max:5',
        'item_rating' => 'nullable|integer|min:1|max:5',
    ]);

    $order = Order::with('items.item')->find($id); // pastikan load item

    if (!$order) {
        return response()->json([
            'message' => 'Pesanan tidak ditemukan'
        ], 404);
    }

    if ($validated['status'] === 'berhasil') {
        if (!isset($validated['restaurant_rating']) || !isset($validated['item_rating'])) {
            return response()->json([
                'message' => 'Rating restoran dan item wajib diisi saat menyelesaikan pesanan'
            ], 422);
        }

        $order->restaurant_rating = $validated['restaurant_rating'];
        $order->item_rating = $validated['item_rating'];
        $order->status = $validated['status'];
        $order->save();

        // 1. Update rating restoran
        if ($order->restaurant_id) {
            $restaurant = Restaurant::find($order->restaurant_id);
            if ($restaurant) {
                $avg = Order::where('restaurant_id', $restaurant->id)
                    ->whereNotNull('restaurant_rating')
                    ->avg('restaurant_rating');

                $restaurant->rate = round($avg, 1);
                $restaurant->rating = Order::where('restaurant_id', $restaurant->id)
                    ->whereNotNull('restaurant_rating')
                    ->count();
                $restaurant->save();
            }
        }

        // 2. Update rating untuk setiap item di pesanan
        foreach ($order->items as $orderItem) {
            $item = $orderItem->item;
            if ($item) {
                $avg = Order::whereHas('items', function ($query) use ($item) {
                        $query->where('item_id', $item->id);
                    })
                    ->whereNotNull('item_rating')
                    ->avg('item_rating');

                $count = Order::whereHas('items', function ($query) use ($item) {
                        $query->where('item_id', $item->id);
                    })
                    ->whereNotNull('item_rating')
                    ->count();

                $item->rate = round($avg, 1);
                $item->rating = $count;
                $item->save();
            }
        }

    } else {
        $order->status = $validated['status'];
        $order->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Status pesanan berhasil diperbarui',
        'data' => $order
    ]);
}

}
