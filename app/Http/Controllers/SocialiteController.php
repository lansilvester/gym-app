<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                if (!$user->is_active) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account has been deactivated. Please contact support.',
                    ]);
                }
                $request->session()->regenerate();
                Auth::login($user, true);
                return redirect()->intended(route('dashboard'));
            }

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                if (!$user->is_active) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account has been deactivated. Please contact support.',
                    ]);
                }
                $user->update(['google_id' => $googleUser->id]);
                $request->session()->regenerate();
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

            Member::create([
                'user_id' => $user->id,
                'member_code' => 'MBR-' . strtoupper(Str::random(8)),
            ]);

            $request->session()->regenerate();
            Auth::login($user, true);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Failed to authenticate with Google. Please try again.',
            ]);
        }
    }
}
