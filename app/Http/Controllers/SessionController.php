<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $agent = new \Jenssegers\Agent\Agent($session->user_agent);
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                    'is_current' => $session->id === request()->session()->getId(),
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    public function destroy(Request $request, string $sessionId)
    {
        if ($sessionId === $request->session()->getId()) {
            return back()->with('error', 'You cannot delete your current session.');
        }

        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $request->user()->id)
            ->delete();

        return back()->with('success', 'Session terminated.');
    }
}
