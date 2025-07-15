<?php

namespace App\Http\Controllers\Admin;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ItemCategoryController extends Controller
{
    /**
     * Ambil daftar semua kategori item.
     */



    public function index()
    {
        Log::info('Fetching all item categories');

        try {
            $categories = ItemCategory::all();
            Log::info('Item categories retrieved', ['count' => $categories->count()]);

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch item categories', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simpan kategori item baru.
     */
    public function store(Request $request)
    {
        Log::info('Storing new item category', ['request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->only(['name']);
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/item_categories', $imageName);
                $data['image'] = $imageName;
            }

            $category = ItemCategory::create($data);

            Log::info('Item category created', ['category' => $category->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create item category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil detail kategori item berdasarkan ID.
     */
    public function show($id)
    {
        Log::info('Fetching item category', ['id' => $id]);

        try {
            $category = ItemCategory::findOrFail($id);
            Log::info('Item category retrieved', ['category' => $category->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch item category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category: ' . $e->getMessage(),
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Perbarui kategori item berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        Log::info('Updating item category', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $category = ItemCategory::findOrFail($id);

            $data = $request->only(['name']);
            if ($request->hasFile('image')) {
                if ($category->image && Storage::exists('public/item_categories/' . $category->image)) {
                    Storage::delete('public/item_categories/' . $category->image);
                }
                $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/item_categories', $imageName);
                $data['image'] = $imageName;
            }

            $category->update($data);

            Log::info('Item category updated', ['category' => $category->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update item category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage(),
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Hapus kategori item berdasarkan ID.
     */
    public function destroy($id)
    {
        Log::info('Deleting item category', ['id' => $id]);

        try {
            $category = ItemCategory::findOrFail($id);

            if ($category->image && Storage::exists('public/item_categories/' . $category->image)) {
                Storage::delete('public/item_categories/' . $category->image);
            }

            $category->delete();

            Log::info('Item category deleted', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete item category', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage(),
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }


    public function  getItemsCategories(){

        // Mengambil semua data kategori item dari database
        $categories = ItemCategory::all();

        // Transformasi data secara manual untuk respons JSON
        $formattedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                // Pastikan 'image' di database menyimpan path relatif dari storage/app/public
                // dan Anda sudah menjalankan 'php artisan storage:link'
                'image' => $category->image,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            ];
        });

        // Mengembalikan respons dalam format JSON
        return response()->json([
            'data' => $formattedCategories,
            'message' => 'Item categories retrieved successfully.',
            'success' => true,
        ]);
    }

}
