<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
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

        return response()->json();
    }

    public function logout(LoginRequest $request)
    {
        $credentials = $request->validated();
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

        $authToken = $user->tokens()->delete();
        auth()->logout();

        return response()->json();
    }
}
