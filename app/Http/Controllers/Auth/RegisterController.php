<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

        ]);

        if ($validator->fails()) {
            return response()->json([
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
            'token' => $token,
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
    }

}
