<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
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
            'id'         => $restaurant->id,
            'name'       => $restaurant->name,
            'location'   => $restaurant->location,
            'phone'      => $restaurant->phone ?? $restaurant->owner->phone ?? null, // 🔧 PENTING
            'type'       => $restaurant->type,
            'food_type'  => $restaurant->food_type,
            'image'      => $restaurant->image,
            'email'      => $restaurant->owner->email ?? null, // <-- ini dia
            // Jangan kembalikan password
        ]
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'location'    => 'required|string',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6',
            'type'        => 'nullable|string|max:100',
            'food_type'   => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan user dengan role restaurant_owner
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'restaurant_owner',
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
            'name'       => $request->name,
            'location'   => $request->location,
            'phone'      => $request->phone,
            'type'       => $request->type,
            'food_type'  => $request->food_type,
            'image'      => $imageFilename,
            'user_id'    => $user->id,
        ]);

        return response()->json(['success' => true, 'data' => $restaurant]);
    }

  public function update(Request $request, $id)
{
    $restaurant = Restaurant::findOrFail($id);
    $user = $restaurant->owner;

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6',
        'phone' => 'nullable|string|max:20',
        'location' => 'nullable|string|max:255',
        'type' => 'nullable|string|max:255',
        'food_type' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Update user
    $user->name = $validated['name'];
    $user->email = $validated['email'] ?? $user->email;
    if (!empty($validated['password'])) {
        $user->password = bcrypt($validated['password']);
    }
    $user->save();

    // Handle upload gambar baru jika ada
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageFilename = 'restaurant_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('restaurants', $imageFilename, 'public');
        $restaurant->image = $imageFilename;
    }

    // Update restoran
    $restaurant->update([
        'name' => $validated['name'],
        'location' => $validated['location'] ?? $restaurant->location,
        'phone' => $validated['phone'] ?? $restaurant->phone,
        'type' => $validated['type'] ?? $restaurant->type,
        'food_type' => $validated['food_type'] ?? $restaurant->food_type,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Restaurant updated',
        'data' => $restaurant,
    ]);
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
