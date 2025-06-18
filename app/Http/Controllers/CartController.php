<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = Cart::with(['item', 'item.restaurant'])->where('user_id', $user->id)->get();
        $cartItems->each(function ($cartItem) {
            if (isset($cartItem->item->restaurant)) {
                $restaurant = $cartItem->item->restaurant;
                $restaurant->image = url('storage/restaurants/' . $restaurant->image);
            }
        });
        $cartItems->each(function ($cartItem) {
            if (isset($cartItem->item)) {
                $cartItem->item->image = url('storage/items/' . $cartItem->item->image); // <-- Simpan URL ke objek

            }
        });
        return response()->json([
            'success' => true,
            'data' => $cartItems
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1);

        // Validasi input
        $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Ambil detail item
        $item = Item::findOrFail($itemId);
        \Log::info('Item price from database: ' . $item->price);

        // Ambil semua item di keranjang user
        $existingCartItems = Cart::where('user_id', $user->id)->with('item')->get();

        // Kalau keranjang tidak kosong, periksa apakah restoran sama
        if ($existingCartItems->isNotEmpty()) {
            $existingRestaurantId = $existingCartItems->first()->item->restaurant_id;
            if ($existingRestaurantId != $item->restaurant_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart con  tains items from a different restaurant. Clear cart to proceed.',
                    'data' => [
                        'current_restaurant_id' => $existingRestaurantId,
                        'new_restaurant_id' => $item->restaurant_id,
                    ],
                ], 409);
            }
        }

        // Cek apakah item udah ada di keranjang
        $cartItem = Cart::where('user_id', $user->id)
            ->where('item_id', $itemId)
            ->first();

        if ($cartItem) {
            // Item udah ada, tambahin quantity
            $cartItem->quantity += $quantity;
            $cartItem->price = $item->price; // update harga kalau berubah
            $cartItem->save();
        } else {
            // Item belum ada, buat baru
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
                'quantity' => $quantity,
                'price' => $item->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'data' => $cartItem
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)->findOrFail($id);
        $cart->update($request->only('quantity'));
        return response()->json($cart);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ], 200);
    }

    public function increase(Request $request)
    {
        $user = $request->user();
        $itemId = $request->input('item_id');

        $cartItem = Cart::where('user_id', $user->id)->where('item_id', $itemId)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        }

        return response()->json(['success' => true, 'message' => 'Quantity increased']);
    }

    public function decrease(Request $request)
    {
        $user = $request->user();
        $itemId = $request->input('item_id');

        $cartItem = Cart::where('user_id', $user->id)->where('item_id', $itemId)->first();

        if ($cartItem) {
            $cartItem->quantity -= 1;
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Quantity decreased']);
    }



}
