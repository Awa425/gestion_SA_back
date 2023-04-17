<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApprenantResource;
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
        $user = Apprenant::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Vérifiez vos informations'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new ApprenantResource($user),
            'token' => $token
        ];

        return response($response, 201);
    }

}
