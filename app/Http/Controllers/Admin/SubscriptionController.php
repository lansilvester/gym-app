<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = MemberSubscription::with('member.user', 'package');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $subscriptions = $query->latest('start_date')->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $members = Member::with('user')->get();
        $packages = MembershipPackage::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.subscriptions.create', compact('members', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'package_id' => 'required|exists:membership_packages,id',
            'start_date' => 'required|date',
            'status' => 'required|in:active,expired,cancelled,suspended',
            'auto_renew' => 'boolean',
            'remaining_PT_sessions' => 'nullable|integer|min:0',
        ]);

        $package = MembershipPackage::findOrFail($validated['package_id']);
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $validated['end_date'] = $startDate->copy()->addDays($package->duration_days);

        MemberSubscription::create($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created.');
    }

    public function edit(MemberSubscription $subscription)
    {
        $subscription->load('member.user', 'package');
        $members = Member::with('user')->get();
        $packages = MembershipPackage::orderBy('sort_order')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'members', 'packages'));
    }

    public function update(Request $request, MemberSubscription $subscription)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'package_id' => 'required|exists:membership_packages,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,cancelled,suspended',
            'auto_renew' => 'boolean',
            'remaining_PT_sessions' => 'nullable|integer|min:0',
            'cancel_reason' => 'nullable|string',
        ]);

        if ($validated['status'] === 'cancelled' && !isset($validated['cancel_reason'])) {
            $validated['cancelled_at'] = now();
        }

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated.');
    }

    public function destroy(MemberSubscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted.');
    }
}
