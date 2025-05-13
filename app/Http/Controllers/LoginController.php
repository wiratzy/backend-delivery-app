<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
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
}
