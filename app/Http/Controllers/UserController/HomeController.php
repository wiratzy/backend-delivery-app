<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\RestaurantCategory;
use App\Models\Restaurant;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // Mengambil daftar kategori makanan (item categories)
    public function getAllCategories(Request $request)
{
    Log::info('Categories accessed', ['user' => $request->user()->toArray()]);

    try {
        $categories = ItemCategory::select('id', 'name', 'image')
            ->get() // Menjalankan query untuk mengambil semua data
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image ?? 'default_category.png',
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully',
            'categories' => $categories,
        ], 200);
    } catch (\Exception $e) {
        Log::error('Failed to retrieve categories', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve categories: ' . $e->getMessage(),
        ], 500);
    }
}
    public function getCategories(Request $request)
    {
        Log::info('Categories accessed', ['user' => $request->user()->toArray()]);

        $categories = ItemCategory::select('id', 'name', 'image')
            ->get(3)
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image ?? 'default_category.png',
                ];
            });

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    // Mengambil daftar restoran populer
    public function getPopularRestaurants(Request $request)
    {
        Log::info('Popular restaurants accessed', ['user' => $request->user()->toArray()]);

        $popularRestaurants = Restaurant::select('id', 'name', 'image', 'rate', 'type')
            ->whereHas('category', function ($query) {
                $query->whereIn('name', ['Cafe', 'Fast Food']);
            })
            ->orderBy('rate', 'desc')
            ->take(3)
            ->get()
            ->map(function ($restaurant) {
                $rateValue = is_numeric($restaurant->rate) ? floatval($restaurant->rate) : 4.9;
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'image' => $restaurant->image ?? 'default_restaurant.png',
                    'rate' => number_format($rateValue, 1),
                    'rating' => (string) rand(100, 200),
                    'type' => $restaurant->type ?? 'Cafe',
                    'food_type' => $restaurant->food_type ?? 'Western Food',
                ];
            });

        return response()->json([
            'popular' => $popularRestaurants,
        ], 200);
    }

    // Mengambil daftar restoran paling populer
    public function getMostPopularRestaurants(Request $request)
    {
        Log::info('Most popular restaurants accessed', ['user' => $request->user()->toArray()]);

        $mostPopularRestaurants = Restaurant::select('id', 'name', 'image', 'rate', 'type')
            ->where('is_most_popular', true)
            ->whereHas('category', function ($query) {
                $query->whereIn('name', ['Cafe', 'Fast Food']);
            })
            ->orderBy('rate', 'desc')
            ->take(2)
            ->get()
            ->map(function ($restaurant) {
                $rateValue = is_numeric($restaurant->rate) ? floatval($restaurant->rate) : 4.9;
                return [
                    'id' => $restaurant->id,
                    'name' => $restaurant->name,
                    'image' => $restaurant->image ?? 'default_restaurant.png',
                    'rate' => number_format($rateValue, 1),
                    'rating' => (string) rand(100, 200),
                    'type' => $restaurant->type ?? 'Cafe',
                    'location' => $restaurant->location ?? 'Western Food',
                ];
            });

        return response()->json([
            'most_popular' => $mostPopularRestaurants,
        ], 200);
    }

    // Mengambil daftar item terbaru
    public function getRecentItems(Request $request)
    {
        Log::info('Recent items accessed', ['user' => $request->user()->toArray()]);

        $recentItems = Item::select('id', 'name', 'image', 'rating', 'type')
            ->whereHas('category', function ($query) {
                $query->whereIn('name', ['Sri Lanka', 'Italian', 'Indian', 'Offer']);
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $ratingValue = is_numeric($item->rating) ? floatval($item->rating) : 4.9;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image ?? 'default_item.png',
                    'rate' => number_format($ratingValue, 1),
                    'rating' => (string) rand(100, 200),
                    'type' => $item->type ?? 'Cafe',
                    'location' => $item->location ?? 'Western Food',
                ];
            });

        return response()->json([
            'recent_items' => $recentItems,
        ], 200);
    }
}
