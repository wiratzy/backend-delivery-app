<?php

namespace App\Http\Controllers\UserController;

use App\Models\Order;
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
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Order Berhasil Ditemukan',
            'data' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:menunggu_konfirmasi,diproses,diantar,berhasil,dibatalkan',
    ]);

    $order = Order::find($id);

    if (!$order) {
        return response()->json([
            'message' => 'Pesanan tidak ditemukan'
        ], 404);
    }

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => 'Status pesanan berhasil diperbarui',
        'data' => $order
    ]);
}
}
