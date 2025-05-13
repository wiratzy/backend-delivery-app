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
    public function index(Request $request)
    {
        if ($request->user()->role === 'customer') {
            return response()->json(Order::where('user_id', $request->user()->id)->with('items')->get());
        } elseif ($request->user()->role === 'restaurant_owner') {
            $restaurantIds = Restaurant::where('owner_id', $request->user()->id)->pluck('id');
            return response()->json(
                Order::whereHas('items', function ($query) use ($restaurantIds) {
                    $query->whereIn('restaurant_id', $restaurantIds);
                })->with('items')->get()
            );
        } elseif ($request->user()->role === 'driver') {
            return response()->json(Order::where('driver_id', $request->user()->id)->with('items')->get());
        }
        return response()->json(Order::with('items')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'payment_method' => 'required',
        ]);

        $carts = Cart::where('user_id', $request->user()->id)->with('item')->get();
        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->item->price * $cart->quantity;
        });

        $order = Order::create([
            'user_id' => $request->user()->id,
            'total_price' => $totalPrice,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cart->item_id,
                'quantity' => $cart->quantity,
                'price' => $cart->item->price,
            ]);
        }

        // Kirim notifikasi ke Restaurant Owner
        $restaurantIds = $carts->pluck('item.restaurant_id')->unique();
        $owners = Restaurant::whereIn('id', $restaurantIds)->pluck('owner_id')->unique();
        foreach ($owners as $ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'title' => 'Pesanan Baru Masuk',
                'message' => "Pesanan #{$order->id} telah diterima.",
                'type' => 'order',
            ]);
        }

        // Kosongkan cart setelah checkout
        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json($order, 201);
    }

    public function show($id)
    {
        return response()->json(Order::with('items')->findOrFail($id));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered',
        ]);

        $order = Order::findOrFail($id);

        if ($request->user()->role === 'restaurant_owner') {
            $restaurantIds = Restaurant::where('owner_id', $request->user()->id)->pluck('id');
            $hasAccess = $order->items()->whereIn('restaurant_id', $restaurantIds)->exists();
            if (!$hasAccess) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } elseif ($request->user()->role === 'driver') {
            if ($order->driver_id !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $order->status = $request->status;
        $order->save();

        // Kirim notifikasi ke Customer
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Status Pesanan Berubah',
            'message' => "Pesanan #{$order->id} sekarang: {$order->status}",
            'type' => 'status_update',
        ]);

        return response()->json($order);
    }

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($id);
        $driver = User::findOrFail($request->driver_id);
        if ($driver->role !== 'driver') {
            return response()->json(['message' => 'User is not a driver'], 400);
        }

        $order->driver_id = $request->driver_id;
        $order->save();

        // Kirim notifikasi ke Driver
        Notification::create([
            'user_id' => $request->driver_id,
            'title' => 'Pesanan Ditugaskan',
            'message' => "Pesanan #{$order->id} telah ditugaskan kepada Anda.",
            'type' => 'assignment',
        ]);

        // Kirim notifikasi ke Customer
        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Driver Ditugaskan',
            'message' => "Pesanan #{$order->id} sedang ditangani oleh driver.",
            'type' => 'status_update',
        ]);

        return response()->json($order);
    }
}
