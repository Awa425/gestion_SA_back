<?php

namespace App\Http\Controllers;

use App\Models\Apprenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApprenantAuth extends Controller
{


    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $apprennant = Apprenant::where('email', $fields['email'])->first();

        // Check password
        if(!$apprennant || !Hash::check($fields['password'], $apprennant->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $apprennant->createToken('mytoken')->plainTextToken;

        $response = [
            'apprennant' => $apprennant,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->apprennant()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
