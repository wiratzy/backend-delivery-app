<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{

    public function index()
    {
        try {
            $users = User::all(); // Mengambil semua data pengguna dari tabel users

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'users' => $users,
            ], 200); // Kode status 200 untuk sukses
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage(),
            ], 500); // Kode status 500 untuk error server
        }
    }

    public function getAllCustomer(){
        try {
            $users = User::where(['role' => 'customer'] )->get(); // Mengambil semua data pengguna dari tabel users

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'users' => $users,
            ], 200); // Kode status 200 untuk sukses
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage(),
            ], 500); // Kode status 500 untuk error server
        }
    }

    public function storeRestaurantOwner(Request $request)
    {
        Log::info('storeRestaurantOwner called', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'restaurant_owner', // Tetapkan role sesuai enum
            ]);

            Log::info('Restaurant owner created', ['user' => $user]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant owner account created successfully',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create restaurant owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create restaurant owner: ' . $e->getMessage(),
            ], 500);
        }

    }
    public function updateRestaurantOwner(Request $request, $id)
    {
        Log::info('updateRestaurantOwner called', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'restaurant_owner') {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a restaurant owner',
                ], 403);
            }

            $user->update([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            $updatedUser = $user->fresh();
            Log::info('Restaurant owner updated', ['user' => $updatedUser]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant owner updated successfully',
                'user' => $updatedUser,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update restaurant owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update restaurant owner: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyRestaurantOwner($id)
    {
        Log::info('destroyRestaurantOwner called', ['id' => $id]);

        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'restaurant_owner') {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a restaurant owner',
                ], 403);
            }

            $user->delete();

            Log::info('Restaurant owner deleted', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Restaurant owner deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete restaurant owner', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete restaurant owner: ' . $e->getMessage(),
            ], 500);
        }
    }
}
