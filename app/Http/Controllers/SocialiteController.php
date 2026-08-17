<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user, true);
                return redirect()->intended(route('dashboard'));
            }

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user, true);
                return redirect()->intended(route('dashboard'));
            }

            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('member');

            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to authenticate with Google. Please try again.',
            ]);
        }
    }
}
