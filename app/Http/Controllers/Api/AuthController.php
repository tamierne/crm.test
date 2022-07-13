<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if(!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Given credentials were invalid',
                'errors' => [
                    'password' => 'invalid password',
                    'email' => 'invalid email',
                ],
            ], 403);
        }

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'you have successfully logged in',
            'access-token' => $authToken,
        ]);
    }

    public function logout(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'message' => 'Given credentials were invalid',
                'errors' => [
                    'password' => 'invalid password',
                    'email' => 'invalid email',
                ],
            ], 403);
        }

        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'you have logged out',
        ]);
    }
}
