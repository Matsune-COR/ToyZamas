<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
     /** /register */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user  = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // その場でトークンも発行
        $token = $user->createToken('default')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ], 201);
    }

    /** /login */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => '認証失敗'], 401);
        }

        // 既存トークンを全部無効にしたいなら ↓ を付ける
        $user->tokens()->delete();

        //is_adminがtrueならabilityとしてadminを付与 違うならuserを付与

        $token = $user->createToken('default')->plainTextToken;



        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /** /logout */
    public function logout(Request $request)
    {
        // そのトークンだけ無効化
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました']);
    }
}
