<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', MemberSubscription::class);

        $query = MemberSubscription::with('member.user', 'package');

        if ($search = $request->input('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('member.user', fn($u) => $u->where('name', 'like', "{$search}%"))
                  ->orWhereHas('package', fn($p) => $p->where('name', 'like', "{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $subscriptions = $query->latest('start_date')->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $this->authorize('create', MemberSubscription::class);

        $members = Member::with('user')->get();
        $packages = MembershipPackage::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.subscriptions.create', compact('members', 'packages'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', MemberSubscription::class);

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'package_id' => 'required|exists:membership_packages,id',
            'start_date' => 'required|date',
            'auto_renew' => 'boolean',
            'remaining_PT_sessions' => 'nullable|integer|min:0',
        ]);

        $validated['status'] = 'active';

        $package = MembershipPackage::findOrFail($validated['package_id']);
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $validated['end_date'] = $startDate->copy()->addDays($package->duration_days);

        MemberSubscription::create($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created.');
    }

    public function edit(MemberSubscription $subscription)
    {
        $this->authorize('update', $subscription);

        $subscription->load('member.user', 'package');
        $members = Member::with('user')->get();
        $packages = MembershipPackage::orderBy('sort_order')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'members', 'packages'));
    }

    public function update(Request $request, MemberSubscription $subscription)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
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
        $this->authorize('delete', $subscription);

        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted.');
    }
}
