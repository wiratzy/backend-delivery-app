<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        Log::info('USER', ['user' => $user]);

        $restaurant = $user->restaurant;
        Log::info('RESTAURANT', ['restaurant' => $restaurant]);


        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restoran tidak ditemukan untuk user ini.'
            ], 404);
        }

        $categoryId = $request->query('item_category_id');

        // Query awal
        $query = Item::where('restaurant_id', $restaurant->id);

        // Jika ada filter kategori, tambahkan
        if ($categoryId) {
            $query->where('item_category_id', $categoryId);
        }

        $items = $query->with('itemCategory')->get();

        if ($categoryId && $items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Item dengan kategori tersebut belum tersedia.',
                'data' => []
            ], 404);
        }


        return response()->json([
            'success' => true,
            'message' => 'Daftar item berhasil diambil',
            'data' => $items
        ]);
    }
    public function store(Request $request)
    {
        Log::info('🏷 restaurant_id dari frontend:', ['id' => $request->restaurant_id]);
        Log::info('📋 Restaurant tersedia:', ['data' => Restaurant::find($request->restaurant_id)]);

        Log::info('store called', ['user_id' => $request->user()->id, 'role' => $request->user()->role, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rate' => 'nullable|numeric|min:0|max:10',
            'rating' => 'nullable|string|max:10',
            'type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'item_category_id' => 'nullable|exists:item_categories,id',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        try {
            if ($request->user()->role === 'owner') {
                $restaurant = $request->user()->restaurant;
                if (!$restaurant || $restaurant->id != $request->restaurant_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ke restoran tidak valid.',
                    ], 403);
                }
            } else {
                $restaurant = Restaurant::findOrFail($request->restaurant_id);
            }

            $data = $request->only(['name', 'rate', 'rating', 'type', 'location', 'price', 'item_category_id', 'restaurant_id']);
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/items', $imageName);
                $data['image'] = $imageName;
            } else {
                $data['image'] = 'default_item.png';
            }

            $item = Item::create($data);

            Log::info('Item created', ['item' => $item->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'item' => $item,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create item', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();
            $restaurant = $user->restaurant;

            if (!$restaurant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Restoran tidak ditemukan untuk user ini.'
                ], 404);
            }

            $item = Item::with([
                'itemCategory',
                'restaurant' => function ($query) {
                    $query->select('id', 'name', 'image', 'rate', 'rating', 'type', 'food_type', 'delivery_fee');
                }
            ])
                ->where('restaurant_id', $restaurant->id)
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
{

    Log::info('update called', ['item_id' => $id, 'request' => $request->all()]);

    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
        'type' => 'required|string|max:255',
        'price' => 'required|numeric',
        'item_category_id' => 'nullable|exists:item_categories,id',
    ]);

    try {
        $item = Item::findOrFail($id);
        Log::info("DEBUG:: RESTAURANT ID ITEM: " . $item->restaurant_id);

        // Cek apakah user adalah owner restoran terkait
        if ($request->user()->role === 'owner') {
            $restaurant = $request->user()->restaurant;
            if (!$restaurant || $restaurant->id != $item->restaurant_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ke restoran tidak valid.',
                ], 403);
            }
        }

        $item->name = $request->name;
        $item->type = $request->type;
        $item->price = $request->price;
        $item->item_category_id = $request->item_category_id;

        // Ganti gambar jika ada file image baru
        if ($request->hasFile('image')) {
            // Hapus file lama (optional)
            if ($item->image && Storage::exists('public/items/' . $item->image)) {
                Storage::delete('public/items/' . $item->image);
            }

            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('public/items', $imageName);
            $item->image = $imageName;
        }

        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diperbarui',
            'item' => $item
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal update item', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal update item: ' . $e->getMessage()
        ], 500);
    }
}


    public function destroy(Request $request, $id)
    {
        Log::info('destroy called', ['item_id' => $id, 'user_id' => $request->user()->id, 'role' => $request->user()->role]);

        try {
            if ($request->user()->role === 'owner') {
                $item = Item::whereHas('restaurant', function ($query) use ($request) {
                    $query->where('owner_id', $request->user()->id);
                })->findOrFail($id);
            } else { // admin
                $item = Item::findOrFail($id);
            }

            if ($item->image && Storage::exists('public/items/' . $item->image)) {
                Storage::delete('public/items/' . $item->image);
            }

            $item->delete();

            Log::info('Item deleted', ['item_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete item', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item: ' . $e->getMessage(),
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }


}
