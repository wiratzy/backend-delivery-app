<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();

        // Ambil isi cart user
        $cartItems = Cart::with('item.restaurant')
            ->where('user_id', $user->id)
            ->get();


        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong',
            ], 400);
        }

        // Hitung subtotal
        $subtotal = $cartItems->reduce(function ($carry, $cartItem) {
            return $carry + ($cartItem->quantity * $cartItem->price);
        }, 0);

        // Ambil info restoran dari item pertama
        $restaurant = optional($cartItems->first()->item)->restaurant;

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Item dalam keranjang tidak memiliki data restoran.',
            ], 400);
        }


        $deliveryFee = (float) ($restaurant->delivery_fee ?? 0);
        $total = (float) ($subtotal + $deliveryFee);

        // dd([
        //     'restaurant_id' => $restaurant->id,
        //     'delivery_fee' => $deliveryFee,
        //     'user_id' => $user->id,
        //     'total_price' => $total,
        //     'status' => 'pending_confirmation',
        //     'order_timeout_at' => now()->addMinutes(5),
        // ]);

        // Buat order
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'total_price' => $total,
            'delivery_fee' => $deliveryFee,
            'status' => 'pending_confirmation',
            'payment_method' => 'COD',
            'order_timeout_at' => now()->addMinutes(5),
        ]);

        // Tambahkan order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        // Hapus cart user
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $order->load('items', 'restaurant'),
        ], 201);
    }
}
