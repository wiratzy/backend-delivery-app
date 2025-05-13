<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // Kode status untuk validasi gagal
        }

        $user = User::create([
            'name' => $request->name,
            'address'=> $request->address,
            'phone'=> $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // default role
        ]);

        // Optional: auto-login user (hapus kalau tidak mau)
        // Auth::login($user);
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek kredensial
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success'=> false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // Ambil user
        $user = Auth::user();

        // (Opsional) Buat token untuk autentikasi selanjutnya (kalau pakai Laravel Sanctum / Passport)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'user' => $user,
            'token' => $token, // Uncomment kalau pakai Sanctum/Passport
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out Berhasil']);
    }
}

