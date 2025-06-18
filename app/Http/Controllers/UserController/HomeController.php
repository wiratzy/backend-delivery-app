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
    //     // Mengambil daftar kategori makanan (item categories)
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
                        'image' => $category->image ? \Storage::url('item_categories/' . $category->image) : 'default_category.png',
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
    //     public function getCategories(Request $request)
//     {
//         Log::info('Categories accessed', ['user' => $request->user()->toArray()]);

    //         $categories = ItemCategory::select('id', 'name', 'image')
//             ->get(3)
//             ->map(function ($category) {
//                 return [
//                     'id' => $category->id,
//                     'name' => $category->name,
//                     'image' => $category->image ?? 'default_category.png',
//                 ];
//             });

    //         return response()->json([
//             'categories' => $categories,
//         ], 200);
//     }

    //     // Mengambil daftar restoran populer
//     public function getPopularRestaurants(Request $request)
//     {
//         Log::info('Popular restaurants accessed', ['user' => $request->user()->toArray()]);

    //         $popularRestaurants = Restaurant::select('id', 'name', 'image', 'rate', 'type')
//             ->whereHas('category', function ($query) {
//                 $query->whereIn('name', ['Cafe', 'Fast Food']);
//             })
//             ->orderBy('rate', 'desc')
//             ->take(3)
//             ->get()
//             ->map(function ($restaurant) {
//                 $rateValue = is_numeric($restaurant->rate) ? floatval($restaurant->rate) : 4.9;
//                 return [
//                     'id' => $restaurant->id,
//                     'name' => $restaurant->name,
//                     'image' => $restaurant->image ?? 'default_restaurant.png',
//                     'rate' => number_format($rateValue, 1),
//                     'rating' => (string) rand(100, 200),
//                     'type' => $restaurant->type ?? 'Cafe',
//                     'food_type' => $restaurant->food_type ?? 'Western Food',
//                 ];
//             });

    //         return response()->json([
//             'popular' => $popularRestaurants,
//         ], 200);
//     }



    //     // Mengambil daftar item terbaru
//     public function getRecentItems(Request $request)
//     {
//         Log::info('Recent items accessed', ['user' => $request->user()->toArray()]);

    //         $recentItems = Item::select('id', 'name', 'image', 'rating', 'type')
//             ->whereHas('category', function ($query) {
//                 $query->whereIn('name', ['Sri Lanka', 'Italian', 'Indian', 'Offer']);
//             })
//             ->orderBy('created_at', 'desc')
//             ->take(3)
//             ->get()
//             ->map(function ($item) {
//                 $ratingValue = is_numeric($item->rating) ? floatval($item->rating) : 4.9;
//                 return [
//                     'id' => $item->id,
//                     'name' => $item->name,
//                     'image' => $item->image ?? 'default_item.png',
//                     'rate' => number_format($ratingValue, 1),
//                     'rating' => (string) rand(100, 200),
//                     'type' => $item->type ?? 'Cafe',
//                     'location' => $item->location ?? 'Western Food',
//                 ];
//             });

    //         return response()->json([
//             'recent_items' => $recentItems,
//         ], 200);
//     }
    public function fetchHomeData(Request $request)
    {
        Log::info('Fetching home data', ['user_id' => $request->user()?->id]);

        try {
            // Ambil kategori makanan
            $categories = ItemCategory::select('id', 'name', 'image')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'image' => $category->image ? url('storage/item_categories/' . $category->image) : 'default_category.png',
                    ];
                });

            // Ambil restoran populer (tetap sama)
            $popularRestaurants = Restaurant::select('restaurants.*')
                ->join('items', 'restaurants.id', '=', 'items.restaurant_id')
                ->join('order_items', 'items.id', '=', 'order_items.item_id')
                ->groupBy('restaurants.id')
                ->orderByRaw('COUNT(order_items.id) DESC')
                ->take(10)
                ->get()
                ->map(function ($restaurant) {
                    return [
                        'id' => $restaurant->id,
                        'name' => $restaurant->name,
                        'image' => $restaurant->image ? url('storage/restaurants/' . $restaurant->image) : 'default_restaurant.png',
                        'rate' => number_format((float) $restaurant->rate, 1),
                        'rating' => $restaurant->rating,
                        'type' => $restaurant->type ?? 'Unknown',
                        'food_type' => $restaurant->food_type ?? 'Unknown',
                    ];
                });

            // Ambil item terbaru (tetap sama)
            $recentItems = Item::select('id', 'name', 'image', 'rate', 'rating', 'type', 'price', 'item_category_id', 'restaurant_id', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image' => $item->image ? \url('storage/items/' . $item->image) : 'default_item.png',
                        'rate' => number_format((float) $item->rate, 1),
                        'rating' => $item->rating,
                        'type' => $item->type ?? 'Unknown',
                        'price' =>  $item->price, 2,
                        'item_category_id' => $item->item_category_id,
                        'restaurant_id' => $item->restaurant_id,
                        'restaurant' => $item->restaurant ? [
                        'id' => $item->restaurant->id,
                        'name' => $item->restaurant->name,
                        'image' => $item->restaurant->image ? url('storage/restaurants/' . $item->restaurant->image) : 'default_restaurant.png',
                        'rate' => number_format((float) $item->restaurant->rate, 1),
                        'rating' => $item->restaurant->rating,
                        'type' => $item->restaurant->type ?? 'Unknown',
                        'food_type' => $item->restaurant->food_type ?? 'Unknown',
                    ] : null,
                        'created_at' => $item->created_at->toIso8601String(),
                        'updated_at' => $item->updated_at->toIso8601String(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'popular' => $popularRestaurants,
                    'recent_items' => $recentItems,
                ],
                'message' => "Berhasil Mengambil Data"
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch home data', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch home data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
