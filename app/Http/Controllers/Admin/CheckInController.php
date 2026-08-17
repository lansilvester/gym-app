<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Member;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $query = CheckIn::with('member.user', 'checkedInBy');

        if ($request->filled('date')) {
            $query->whereDate('check_in_at', $request->input('date'));
        } else {
            $query->whereDate('check_in_at', now()->toDateString());
        }

        if ($search = $request->input('search')) {
            $query->whereHas('member.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('member', function ($q) use ($search) {
                $q->where('member_code', 'like', "%{$search}%");
            });
        }

        $checkIns = $query->latest('check_in_at')->paginate(20);
        $members = Member::with('user')->orderBy('id')->get();
        return view('admin.checkins.index', ['checkins' => $checkIns, 'members' => $members]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required_without:member_code|exists:members,id',
            'member_code' => 'required_without:member_id|string',
            'method' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['member_code']) && empty($validated['member_id'])) {
            $member = Member::where('member_code', $validated['member_code'])->firstOrFail();
            $validated['member_id'] = $member->id;
        }
        unset($validated['member_code']);

        $validated['check_in_at'] = now();
        $validated['checked_in_by'] = auth()->id();

        CheckIn::create($validated);

        return redirect()->route('admin.checkins.index')->with('success', 'Member checked in successfully.');
    }

    public function checkOut(CheckIn $checkIn)
    {
        if ($checkIn->check_out_at) {
            return back()->with('error', 'Member has already checked out.');
        }

        $checkIn->update(['check_out_at' => now()]);

        return redirect()->route('admin.checkins.index')->with('success', 'Member checked out successfully.');
    }
}
