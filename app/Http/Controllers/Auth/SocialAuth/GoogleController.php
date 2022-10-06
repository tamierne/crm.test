<?php

namespace App\Http\Controllers\Auth\SocialAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'password' => Hash::make('gglpwd059'),
//            'google_token' => $googleUser->token,
//            'google_refresh_token' => $googleUser->refreshToken,
        ]);

        $user->markEmailAsVerified();

        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('admin.index');
    }
}
