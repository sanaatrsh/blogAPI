<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccessTokensController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken();

            return response()->json([
                'token' => $token->plainTextToken,
                'user' => $user,
            ], 201);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
}
