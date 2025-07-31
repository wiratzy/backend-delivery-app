<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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

      public function show(User $user)
    {
        // Berkat Route Model Binding, $user sudah merupakan instance dari user yang dicari.
        return response()->json($user);
    }

    /**
     * Mengupdate data pengguna oleh admin.
     * PUT /api/admin/users/{user}
     */
  public function update(Request $request, User $user)
{
    // Debugging (opsional): Pastikan Anda mendapatkan user yang benar dari URL
    // dd($user->toArray());

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            // ### INI SOLUSINYA ###
            // Serahkan seluruh object $user, Laravel akan otomatis menangani primary key-nya.
            Rule::unique('users')->ignore($user),
        ],
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'role' => ['required', Rule::in(['customer', 'admin', 'owner', 'driver'])],
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user->update($validator->validated());
    $user->refresh();

    return response()->json([
        'message' => 'Pengguna berhasil diperbarui.',
        'user' => $user,
    ]);
}

    /**
     * Menghapus pengguna dari database.
     * DELETE /api/admin/users/{user}
     */
    public function delete(User $user)
    {
        // Keamanan tambahan: Admin tidak bisa menghapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
        }

        try {
            // Hapus foto jika ada (opsional, tapi praktik yang baik)
            // if ($user->photo) {
            //     Storage::disk('public')->delete('photos/' . $user->photo);
            // }

            $user->delete();

            return response()->json(['message' => 'Pengguna berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus pengguna.', 'error' => $e->getMessage()], 500);
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
                'role' => 'owner', // Tetapkan role sesuai enum
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

            if ($user->role !== 'owner') {
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

            if ($user->role !== 'owner') {
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
