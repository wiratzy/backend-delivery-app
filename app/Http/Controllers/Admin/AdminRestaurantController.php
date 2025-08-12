<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminRestaurantController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $searchQuery = $request->get('search');

        // Menggunakan eager loading 'owner'
        $query = Restaurant::with('owner'); // <-- Tambahkan eager loading ini

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('location', 'like', "%{$searchQuery}%")
                    ->orWhere('type', 'like', "%{$searchQuery}%")
                    ->orWhere('food_type', 'like', "%{$searchQuery}%");
                // Jika ingin mencari berdasarkan nama/email owner
                $q->orWhereHas('owner', function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('email', 'like', "%{$searchQuery}%");
                });
            });
        }

        $restaurants = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Daftar restoran berhasil diambil.',
            'data' => $restaurants
        ]);
    }

    public function show($id)
    {
        $restaurant = Restaurant::with('owner')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'location' => $restaurant->location,
                'phone' => $restaurant->phone ?? $restaurant->owner->phone ?? null, // 🔧 PENTING
                'type' => $restaurant->type,
                'food_type' => $restaurant->food_type,
                'image' => $restaurant->image,
                'email' => $restaurant->owner->email ?? null, // <-- ini dia
                // Jangan kembalikan password
            ]
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'type' => 'nullable|string|max:100',
            'food_type' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan user dengan role owner
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'owner',
        ]);

        // Upload image jika ada
        $imageFilename = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageFilename = 'restaurant_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('restaurants', $imageFilename, 'public');
        }

        // Simpan restaurant
        $restaurant = Restaurant::create([
            'name' => $request->name,
            'location' => $request->location,
            'phone' => $request->phone,
            'type' => $request->type,
            'food_type' => $request->food_type,
            'image' => $imageFilename,
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => true, 'data' => $restaurant]);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        // Pastikan restoran memiliki pemilik
        if (!$restaurant->owner) {
            return response()->json([
                'success' => false,
                'message' => 'Restoran ini tidak memiliki data pemilik yang valid.',
            ], 404);
        }
        $user = $restaurant->owner;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'required|string|max:20', // Jadikan 'required' agar konsisten
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'food_type' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Gunakan transaksi untuk memastikan semua query berhasil atau gagal bersamaan
            DB::transaction(function () use ($request, $restaurant, $user, $validated) {

                // 1. UPDATE DATA PEMILIK (USER) TERLEBIH DAHULU
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->phone = $validated['phone']; // <-- PERBAIKAN: Update nomor telepon user

                if (!empty($validated['password'])) {
                    $user->password = bcrypt($validated['password']);
                }
                $user->save();

                // 2. UPDATE DATA RESTORAN
                $restaurant->name = $validated['name'];
                $restaurant->location = $validated['location'];
                $restaurant->type = $validated['type'];
                $restaurant->food_type = $validated['food_type'];

                // SINKRONISASI: Ambil nomor telepon dari user yang sudah di-update
                $restaurant->phone = $user->phone;

                // Handle upload gambar baru jika ada
                if ($request->hasFile('image')) {
                    // Hapus gambar lama jika bukan gambar default
                    if ($restaurant->image && $restaurant->image != 'default_restaurant.png') {
                        Storage::disk('public')->delete($restaurant->image);
                    }
                    // Simpan gambar baru dan update path
                    $path = $request->file('image');
                    $originalExtension = pathinfo($path->getRawOriginal('image'), PATHINFO_EXTENSION);

                    $restaurant->image = $path;
                }

                $restaurant->save();
            });

            $restaurant->load('owner');

            return response()->json([
                'success' => true,
                'message' => 'Data restoran berhasil diperbarui',
                'data' => $restaurant,
            ]);

        } catch (\Exception $e) {
            \Log::error('Update restaurant failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
            ], 500);
        }
    }


    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        // Hapus gambar jika ada
        if ($restaurant->image && Storage::disk('public')->exists($restaurant->image)) {
            Storage::disk('public')->delete($restaurant->image);
        }

        // Hapus user juga (pemilik resto)
        $user = $restaurant->user;
        $restaurant->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json(['success' => true, 'message' => 'Restoran berhasil dihapus']);
    }
}
