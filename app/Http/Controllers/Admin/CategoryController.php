<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Create: Tambah kategori restoran baru (hanya untuk admin)
    public function storeRestaurantCategory(Request $request)
    {
        Log::info('storeRestaurantCategory called', $request->all());

        $request->validate([
            'name' => 'required|string|max:255|unique:restaurant_categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imageUrl = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('categories/restaurants', 'public');
                $imageUrl = Storage::url($path);
            }

            $category = RestaurantCategory::create([
                'name' => $request->name,
                'image' => $imageUrl,
            ]);

            Log::info('Category created', ['category' => $category]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant category created successfully',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create restaurant category: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Read: Ambil daftar semua kategori restoran
    public function indexRestaurantCategories()
    {
        Log::info('indexRestaurantCategories called');

        try {
            $categories = RestaurantCategory::all();

            Log::info('Categories retrieved', ['count' => $categories->count()]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant categories retrieved successfully',
                'categories' => $categories,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve categories', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant categories: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Read: Ambil detail kategori restoran berdasarkan ID
    public function showRestaurantCategory($id)
    {
        Log::info('showRestaurantCategory called', ['id' => $id]);

        try {
            $category = RestaurantCategory::findOrFail($id);

            Log::info('Category retrieved', ['category' => $category]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant category retrieved successfully',
                'category' => $category,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve restaurant category: ' . $e->getMessage(),
            ], 404);
        }
    }

    // Update: Edit kategori restoran (hanya untuk admin)
    public function updateRestaurantCategory(Request $request, $id)
    {
        // Log semua data request yang masuk, termasuk header Content-Type
        Log::info('updateRestaurantCategory called', [
            'id' => $id,
            'request' => $request->all(),
            'files' => $request->hasFile('image') ? $request->file('image')->getClientOriginalName() : 'No file uploaded',
            'content_type' => $request->header('Content-Type'),
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:restaurant_categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $category = RestaurantCategory::findOrFail($id);

            $imageUrl = $category->image;
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($imageUrl && Storage::exists(str_replace(url('storage'), 'public', $imageUrl))) {
                    Storage::delete(str_replace(url('storage'), 'public', $imageUrl));
                }
                $path = $request->file('image')->store('categories/restaurants', 'public');
                $imageUrl = Storage::url($path);
            }

            $category->update([
                'name' => $request->name,
                'image' => $imageUrl ?: $category->image, // Hanya ubah image jika ada file baru
            ]);

            $updatedCategory = $category->fresh();
            Log::info('Category updated', ['category' => $updatedCategory]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant category updated successfully',
                'category' => $updatedCategory,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update restaurant category: ' . $e->getMessage(),
            ], 422);
        }
    }

    // Delete: Hapus kategori restoran (hanya untuk admin)
    public function destroyRestaurantCategory($id)
    {
        Log::info('destroyRestaurantCategory called', ['id' => $id]);

        try {
            $category = RestaurantCategory::findOrFail($id);

            // Hapus gambar jika ada
            if ($category->image && Storage::exists(str_replace(url('storage'), 'public', $category->image))) {
                Storage::delete(str_replace(url('storage'), 'public', $category->image));
            }

            $category->delete();

            Log::info('Category deleted', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete restaurant category: ' . $e->getMessage(),
            ], 500);
        }
    }
}
