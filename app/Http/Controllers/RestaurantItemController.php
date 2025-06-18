<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class RestaurantItemController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

    // Pastikan user memiliki restoran
    $restaurant = $user->restaurant;
    if (!$restaurant) {
        return response()->json(['success' => false, 'message' => 'Restoran tidak ditemukan'], 404);
    }

    // Ambil parameter filter (jika ada)
    $categoryId = $request->query('category_id');
    if (!$categoryId){
        return response()->json(['success' => false, 'message' => "category tidak ditemukan "]);
    }

    $itemsQuery = Item::with('itemCategory')
        ->where('restaurant_id', $restaurant->id);

    // Terapkan filter jika ada
    if ($categoryId) {
        $itemsQuery->where('item_category_id', $categoryId);
    }

    $items = $itemsQuery->get();

    return response()->json([
        'success' => true,
        'data' => $items
    ]);
    }

}
