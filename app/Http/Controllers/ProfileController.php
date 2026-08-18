<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Cascade delete related data
        if ($user->member) {
            $user->member->checkIns()->delete();
            $user->member->invoices()->each(function ($invoice) {
                $invoice->items()->delete();
                $invoice->payments()->delete();
            });
            $user->member->subscriptions()->delete();
            $user->member->ptBookings()->delete();
            $user->member->bodyMeasurements()->delete();
            $user->member->medicalInfo()->delete();
            $user->member->delete();
        }

        if ($user->trainer) {
            $user->trainer->schedules()->delete();
            $user->trainer->ptBookings()->delete();
            $user->trainer->delete();
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
