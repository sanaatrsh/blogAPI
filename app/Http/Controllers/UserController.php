<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data =  $request->validate([
            'name' => 'string|required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'string'
        ]);

        // $request['password'] = Hash::make($request->password);

        $user = User::create($data);
        $token =  $user->createToken($request->userAgent());

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user
        ], 201);

        return response()->json([
            'message' => 'no token baby'
        ], 401);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $data =   $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',

        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token =  $user->createToken($request->userAgent());

            return response()->json([
                'token' => $token->plainTextToken,
                'message' => 'login done',
                'user' => $user
            ], 201);
        }

        return response()->json([
            'message' => 'no token baby'
        ], 401);
    }

    public function logout($token = null)
    {
        $user = Auth::guard('sanctum')->user();

        if (null === $token) {
            $user->currentAccessToken()->delete;
            return response()->json(['message' => 'Successfully logged out']);
        }

        $pat = PersonalAccessToken::findToken($token);
        if ($user->id == $pat->tokenable_id) {
            $pat->delete();
        }


        return response()->json(['error' => 'Unauthorized'], 401);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
