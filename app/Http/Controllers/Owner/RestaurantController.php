<?php

namespace App\Http\Controllers\Owner;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{

    public function index(Request $request)
    {
        Log::info('indexOwnerRestaurants called', ['user_id' => $request->user()->id]);
        Log::info('User details', ['user' => $request->user()->toArray()]);
        try {
            $userId = (int) $request->user()->id; // Pastikan user_id adalah integer
            $restaurants = Restaurant::where('owner_id', $userId)->get();

            Log::info('Restaurants retrieved for owner', [
                'count' => $restaurants->count(),
                'restaurants' => $restaurants->toArray(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurants retrieved successfully',
                'restaurants' => $restaurants,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve restaurants for owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurants: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function showOwnerRestaurant(Request $request, $id)
    {
        Log::info('showOwnerRestaurant called', [
            'restaurant_id' => $id,
            'user_id' => $request->user()->id,
            'user_role' => $request->user()->role
        ]);

        try {
            // Cek apakah restoran dengan ID tersebut ada
            $restaurant = Restaurant::find($id);
            if (!$restaurant) {
                Log::warning('Restaurant not found', ['restaurant_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Restaurant not found',
                ], 404);
            }

            // Log detail restoran yang ditemukan
            Log::info('Restaurant found', ['restaurant' => $restaurant->toArray()]);

            // Cek apakah owner_id cocok dengan user yang sedang login
            if ($restaurant->owner_id !== $request->user()->id) {
                Log::warning('Restaurant does not belong to this owner', [
                    'restaurant_id' => $id,
                    'restaurant_owner_id' => $restaurant->owner_id,
                    'logged_in_user_id' => $request->user()->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this restaurant',
                ], 403);
            }

            Log::info('Restaurant retrieved for owner', ['restaurant' => $restaurant->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant retrieved successfully',
                'restaurant' => $restaurant,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve restaurant for owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeRestaurant(Request $request)
{
    $userId = (int) $request->user()->id; // Ambil user_id dari pengguna yang login
    Log::info('storeRestaurant called', ['user_id' => $userId, 'request' => $request->all()]);

    // Periksa peran pengguna
    if ($request->user()->role !== 'restaurant_owner') {
        Log::warning('Unauthorized attempt to create restaurant', ['user_id' => $userId]);
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: Only restaurant owners can create restaurants',
        ], 403);
    }

    // Validasi input
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rate' => 'nullable|numeric|min:0|max:99.9|regex:/^\d+(\.\d{1})?$/', // Sesuai decimal(3,1)
            'rating' => 'nullable|integer|min:0|max:5', // Sesuai tipe integer
            'type' => 'required|string|max:255',
            'food_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_most_popular' => 'nullable|boolean',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    }

    // Siapkan data untuk disimpan
    $data = [
        'owner_id' => $userId,
        'name' => $request->name,
        'rate' => $request->rate ?? 0.0,
        'rating' => $request->rating ?? 0,
        'type' => $request->type,
        'food_type' => $request->food_type ?? null,
        'location' => $request->location ?? null,
        'is_most_popular' => $request->is_most_popular ?? false,
    ];

    // Tangani upload gambar
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('restaurants', $imageName, 'public');
        if ($path) {
            $data['image'] = $imageName; // Simpan nama file
        } else {
            Log::error('Failed to upload image', ['user_id' => $userId]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
            ], 500);
        }
    } else {
        $data['image'] = 'default_restaurant.png';
    }

    // Buat restoran
    try {
        $restaurant = Restaurant::create($data);

        Log::info('Restaurant created', ['restaurant' => $restaurant->toArray()]);

        // Transform image path untuk frontend
        $restaurant->image = $restaurant->image ? Storage::url('restaurants/' . $restaurant->image) : null;

        return response()->json([
            'success' => true,
            'message' => 'Restaurant created successfully',
            'restaurant' => $restaurant,
        ], 201);
    } catch (\Exception $e) {
        Log::error('Failed to create restaurant', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to create restaurant: ' . $e->getMessage(),
        ], 500);
    }
}


    public function update(Request $request)
    {
        $id = $request->user()->id;
        Log::info('update called', ['id' => $id, 'user_id' => $request->user()->id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'string|max:255',
            'type' => 'string|max:255',
            'food_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'restaurant_category_id' => 'nullable|exists:restaurant_categories,id',
            'image' => 'nullable|image|max:2048', // Validasi untuk file image
        ]);

        try {
            $restaurant = Restaurant::where('id', $id)
                ->where('owner_id', $request->user()->id)
                ->firstOrFail();

            $data = $request->only(['name', 'type', 'food_type', 'location', 'restaurant_category_id']);

            // Handle image upload jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($restaurant->image && Storage::exists('public/restaurants/' . $restaurant->image)) {
                    Storage::delete('public/restaurants/' . $restaurant->image);
                }

                // Simpan gambar baru
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/restaurants', $imageName);
                $data['image'] = $imageName;
            }

            $restaurant->update($data);

            $updatedRestaurant = $restaurant->fresh();
            Log::info('Restaurant updated by owner', ['restaurant' => $updatedRestaurant]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant updated successfully',
                'restaurant' => $updatedRestaurant,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update restaurant by owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update restaurant: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        Log::info('destroyOwnerRestaurant called', ['id' => $id, 'user_id' => $request->user()->id]);

        try {
            $restaurant = Restaurant::where('id', $id)
                ->where('owner_id', $request->user()->id)
                ->firstOrFail();

            $restaurant->delete();

            Log::info('Restaurant deleted by owner', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete restaurant by owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete restaurant: ' . $e->getMessage(),
            ], 500);
        }
    }



public function indexForCustomer(Request $request)
{
    $query = Restaurant::query()->with('owner');

    // 🔍 Filter search by name (optional)
    if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // ⭐ Filter by minimum rate (optional)
    if ($request->has('rate') && is_numeric($request->rate)) {
        $query->where('rate', '>=', $request->rate);
    }

    $restaurants = $query->latest()->get();

    $data = $restaurants->map(function ($r) {
        return [
            'id'         => $r->id,
            'name'       => $r->name,
            'location'   => $r->location,
            'phone'      => $r->phone ?? null,
            'type'       => $r->type,
            'food_type'  => $r->food_type,
            'rate'       => $r->rate,
            'rating'     => $r->rating,
            'image'      => $r->image,
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
}


public function showForCustomer($id)
{
    $restaurant = Restaurant::with('items')->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'location' => $restaurant->location,
            'phone' => $restaurant->phone,
            'type' => $restaurant->type,
            'rate' => $restaurant->rate,
            'rating' => $restaurant->rating,
            'food_type' => $restaurant->food_type,
            'image' => $restaurant->image,
            'items' => $restaurant->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image' => $item->image,
                    'item_category_id' => $item->item_category_id,
                    'restaurant_id' => $item->restaurant_id,
                    'type' => $item->type,
                    'rate' => $item->rate,
                    'rating' => $item->rating,
                ];
            }),
        ]
    ]);
}

}
