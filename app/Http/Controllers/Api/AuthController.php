<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Akun tidak ditemukan'
            ], 401);
        }

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'status' => 'Success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Token tidak valid atau sudah kadaluarsa'
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Logout berhasil'
        ]);
    }
}