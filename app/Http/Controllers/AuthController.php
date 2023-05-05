<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApprenantResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{



    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('User Token')->plainTextToken;
        $response = [
            'user' => new UserResource($user),
            'token' => $token
        ];
        return response($response, 201);
    } elseif (Auth::guard('apprenant')->attempt($credentials)) {
        $user = Auth::guard('apprenant')->user();
        $token = $user->createToken('apprenant Token')->plainTextToken;
        $response = [
            'user' => new ApprenantResource($user),
            'token' => $token
        ];
        return response($response, 201);
    } else {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
}

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
