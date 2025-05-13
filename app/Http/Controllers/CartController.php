<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Cart::where('user_id', $request->user()->id)->with('item')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::create([
            'user_id' => $request->user()->id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)->findOrFail($id);
        $cart->update($request->only('quantity'));
        return response()->json($cart);
    }

    public function destroy($id, Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->findOrFail($id);
        $cart->delete();
        return response()->json(null, 204);
    }
}
