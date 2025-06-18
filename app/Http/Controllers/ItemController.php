<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request, $restaurantId)
    {
        Log::info('Fetching items for restaurant', ['restaurant_id' => $restaurantId, 'user_id' => $request->user()?->id]);

        // Periksa apakah restoran ada
        $restaurant = Restaurant::find($restaurantId);
        if (!$restaurant) {
            Log::warning('Restaurant not found', ['restaurant_id' => $restaurantId]);
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found',
            ], 404);
        }

        // Jika pengguna adalah restaurant_owner, pastikan mereka hanya mengakses restoran mereka
        if ($request->user() && $request->user()->role === 'restaurant_owner') {
            if ($restaurant->owner_id !== $request->user()->id) {
                Log::warning('Unauthorized access to restaurant items', [
                    'user_id' => $request->user()->id,
                    'restaurant_id' => $restaurantId,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You can only access items from your own restaurant',
                ], 403);
            }
        }

        // Ambil items dengan filter opsional
        $query = Item::where('restaurant_id', $restaurantId)
            ->with(['category']); // Eager load relasi item_category

        // Filter opsional berdasarkan parameter kueri
        if ($request->has('category_id')) {
            $query->where('item_category_id', $request->category_id);
        }
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Pagination untuk efisiensi
        $items = $query->paginate(10);

        // Transform image path untuk frontend
        $items->getCollection()->transform(function ($item) {
            $item->image = $item->image ? \Storage::url('items/' . $item->image) : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items,
        ], 200);

    }

    public function getAllItems()
    {
        $items = Item::all();

        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items
        ]);
    }

    public function getItemsByCategory($categoryId)
    {
        Log::info('Items by category accessed', ['category_id' => $categoryId]);

        try {
            $items = Item::where('item_category_id', $categoryId)
                ->select('id', 'name', 'image', 'rate', 'rating', 'type', 'price', 'item_category_id', 'restaurant_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image' => $item->image ? url('storage/items/' . $item->image) : 'default_category.png',
                        'rate' => $item->rate,
                        'rating' => $item->rating,
                        'type' => $item->type,
                        'price' => $item->price,
                        'item_category_id' => $item->item_category_id,
                        'restaurant_id' => $item->restaurant_id,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Items retrieved successfully',
                'items' => $items,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve items', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve items: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function recent()
    {

        Log::info(response()->json(Item::orderBy('created_at', 'desc')->get()));
        return response()->json(Item::orderBy('created_at', 'desc')->take(10)->get());
    }

    public function store(Request $request)
    {
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
            if ($request->user()->role === 'restaurant_owner') {
                $restaurant = Restaurant::where('owner_id', $request->user()->id)
                    ->findOrFail($request->restaurant_id);
            } else { // admin
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

    public function storeForAdmin(Request $request, $restaurant_id)
    {
        Log::info('storeForAdmin called', ['user_id' => $request->user()->id, 'restaurant_id' => $restaurant_id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rate' => 'nullable|numeric|min:0|max:10',
            'rating' => 'nullable|string|max:10',
            'type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'item_category_id' => 'nullable|exists:item_categories,id',
        ]);

        try {
            $restaurant = Restaurant::findOrFail($restaurant_id);

            $data = array_merge(
                $request->only(['name', 'rate', 'rating', 'type', 'location', 'price', 'item_category_id']),
                ['restaurant_id' => $restaurant_id]
            );
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/items', $imageName);
                $data['image'] = $imageName;
            } else {
                $data['image'] = 'default_item.png';
            }

            $item = Item::create($data);

            Log::info('Item created by admin', ['item' => $item->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Item created successfully by admin',
                'item' => $item,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create item by admin', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $item = Item::with([
                'restaurant' => function ($query) {
                    $query->select('id', 'name', 'image', 'rate', 'rating', 'type', 'food_type', 'location', 'delivery_fee');
                }
            ])->findOrFail($id);

            $item->image = $item->image ? \Storage::url('items/' . $item->image) : \Storage::url('default_item.png');
            $item->restaurant->image = $item->restaurant->image ? \Storage::url('restaurants/' . $item->restaurant->image) : \Storage::url('default_item.png');
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
        Log::info('update called', ['item_id' => $id, 'user_id' => $request->user()->id, 'role' => $request->user()->role, 'request' => $request->all()]);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'rate' => 'nullable|numeric|min:0|max:10',
            'rating' => 'nullable|string|max:10',
            'type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'item_category_id' => 'nullable|exists:item_categories,id',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        try {
            if ($request->user()->role === 'restaurant_owner') {
                $item = Item::whereHas('restaurant', function ($query) use ($request) {
                    $query->where('owner_id', $request->user()->id);
                })->findOrFail($id);
            } else { // admin
                $item = Item::findOrFail($id);
            }

            $data = $request->only(['name', 'rate', 'rating', 'type', 'location', 'price', 'item_category_id', 'restaurant_id']);
            if ($request->hasFile('image')) {
                if ($item->image && Storage::exists('public/items/' . $item->image)) {
                    Storage::delete('public/items/' . $item->image);
                }
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/items', $imageName);
                $data['image'] = $imageName;
            }

            $item->update($data);

            Log::info('Item updated', ['item' => $item->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'item' => $item,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update item', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item: ' . $e->getMessage(),
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Hapus data item berdasarkan ID dan role pengguna.
     */
    public function destroy(Request $request, $id)
    {
        Log::info('destroy called', ['item_id' => $id, 'user_id' => $request->user()->id, 'role' => $request->user()->role]);

        try {
            if ($request->user()->role === 'restaurant_owner') {
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
