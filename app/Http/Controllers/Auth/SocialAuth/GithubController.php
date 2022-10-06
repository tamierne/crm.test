<?php

namespace App\Http\Controllers\Auth\SocialAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => Hash::make('gitpwd059'),
//            'github_token' => $githubUser->token,
//            'github_refresh_token' => $githubUser->refreshToken,
        ]);

        $user->markEmailAsVerified();

        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('admin.index');
    }
}
