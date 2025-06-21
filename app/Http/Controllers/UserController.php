<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function update(Request $request)

    {    Log::info('Update request received:', $request->all());

        // return response()->json($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'address_latitude' => 'required',
            'address_longitude' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->address_latitude = $request->address_latitude;   // mapping dari frontend
        $user->address_longitude = $request->address_longitude; // mapping dari frontend

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Success!',
            'user' => $user,
        ]);
    }


    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png|max:2048', // Maks 2MB
        ]);

        $user = $request->user();
        $file = $request->file('photo');
        $filename = 'user_' . $user->id . '.' . $file->getClientOriginalExtension();

        // Simpan file di storage/public/photos
        $path = $file->storeAs('photos', $filename, 'public');

        // Update kolom photo
        $user->photo = $filename;
        $user->save();

        return response()->json([
            'user' => $user,
        ]);
    }
}
