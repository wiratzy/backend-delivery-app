<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AdminRestaurantController extends Controller
{
    public function getAllRestaurant()
{
    Log::info('All Restaurants called');

    try {
        // Mengambil semua pengguna dengan role 'restaurant' dan eager load restoran yang dimiliki
        $restaurantOwners = User::whereHas('roles', function ($query) {
            $query->where('name', 'restaurant'); // Filter role 'restaurant'
        })->with('restaurants')->get(); // Eager load relasi 'restaurants'

        Log::info('Restaurant owners retrieved', ['count' => $restaurantOwners->count()]);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant owners retrieved successfully',
            'restaurant' => $restaurantOwners,
        ], 200);
    } catch (\Exception $e) {
        Log::error('Failed to retrieve restaurant owners', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve restaurant owners: ' . $e->getMessage(),
        ], 500);
    }
}
    public function index()
    {
        Log::info('indexRestaurantOwners called');

        try {
            $restaurant_owners = User::where('role', 'restaurant_owner')
                ->with('restaurants') // Eager load restoran yang dimiliki
                ->get();

            Log::info('Restaurant owners retrieved', ['count' => $restaurant_owners->count()]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant owners retrieved successfully',
                'restaurant_owners' => $restaurant_owners,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve restaurant owners', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant owners: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showRestaurantOwner($id)
    {
        Log::info('show called', ['id' => $id]);

        try {
            $restaurant = Restaurant::findOrFail($id);

            Log::info('Restaurant retrieved', ['restaurant' => $restaurant]);
            return response()->json([
                'success' => true,
                'message' => 'Restaurant retrieved successfully',
                'restaurant' => $restaurant,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve restaurant', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant: ' . $e->getMessage(),
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        Log::info('update called', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'rate' => 'nullable|numeric|min:0|max:10',
            'rating' => 'nullable|string|max:10',
            'type' => 'required|string|max:255',
            'food_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_most_popular' => 'nullable|boolean',
            'restaurant_category_id' => 'nullable|exists:restaurant_categories,id',
        ]);

        try {
            $restaurant = Restaurant::findOrFail($id);

            $restaurant->update([
                'name' => $request->name,
                'image' => $request->image ?? $restaurant->image,
                'rate' => $request->rate ?? $restaurant->rate,
                'rating' => $request->rating ?? $restaurant->rating,
                'type' => $request->type,
                'food_type' => $request->food_type,
                'location' => $request->location,
                'is_most_popular' => $request->is_most_popular ?? $restaurant->is_most_popular,
                'restaurant_category_id' => $request->restaurant_category_id,
            ]);

            $updatedRestaurant = $restaurant->fresh();
            Log::info('Restaurant updated by admin', ['restaurant' => $updatedRestaurant]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant updated successfully',
                'restaurant' => $updatedRestaurant,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update restaurant', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update restaurant: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        Log::info('destroy called', ['id' => $id]);

        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->delete();

            Log::info('Restaurant deleted by admin', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete restaurant', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete restaurant: ' . $e->getMessage(),
            ], 500);
        }
    }

}
