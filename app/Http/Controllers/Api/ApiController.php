<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    // Register API (POST)
    public function register(Request $request)
    {
        // Data validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
        ]);

        // Create User
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => Hash::make($request->password)
        ]);
    }
    // Login API (POST)
    public function login(Request $request)
    {
        // Data validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Check User Login
        if (Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {

            $user = Auth::user();

            $token = $user->createToken("myToken")->accessToken;

            return response()->json([
                "status" => true,
                "message" => "User logged in successfully",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalidate login details"
            ]);
        }
    }

    // Profile API (POST)
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            "status" => true,
            "message" => "Profile info",
            "data" => $user
        ]);

    }

    // Logout API (POST)
    public function logout()
    {
         auth()->user()->token()->revoke();

         return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);

         
        
    }
}
