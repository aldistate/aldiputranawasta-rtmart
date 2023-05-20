<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // jika validasi gagal, munculkan pesan errornya
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $credentials = $request->only('email', 'password');

        // jika inputan benar
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json([
                'token' => $token
            ], 200);
        } else {
            // jika gagal
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }

    // untuk mengecek autentikasi yg masuk
    public function authCheck()
    {
        return response()->json([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email
        ]);
    }
}
