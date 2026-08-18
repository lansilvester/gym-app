<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\PtBooking;
use App\Models\Trainer;
use App\Models\MemberSubscription;
use Illuminate\Http\Request;

class PtBookingController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', PtBooking::class);

        $query = PtBooking::with('member.user', 'trainer.user');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->input('date'));
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('booking_date', [$request->input('from'), $request->input('to')]);
        }

        $bookings = $query->latest('booking_date')->latest('start_time')->paginate(15);
        return view('admin.pt-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $this->authorize('create', PtBooking::class);

        $members = Member::with('user', 'activeSubscription.package')
            ->whereHas('activeSubscription')
            ->get();
        $trainers = Trainer::with('user')->where('is_available', true)->get();
        return view('admin.pt-bookings.create', compact('members', 'trainers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', PtBooking::class);

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'trainer_id' => 'required|exists:trainers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'session_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $member = Member::with('activeSubscription')->findOrFail($validated['member_id']);
        $validated['subscription_id'] = $member->activeSubscription?->id;
        $validated['status'] = 'booked';

        PtBooking::create($validated);

        return redirect()->route('admin.pt-bookings.index')->with('success', 'Booking created successfully.');
    }

    public function show(PtBooking $ptBooking)
    {
        $this->authorize('view', $ptBooking);

        $ptBooking->load('member.user', 'trainer.user', 'subscription.package');
        return view('admin.pt-bookings.show', compact('ptBooking'));
    }

    public function updateStatus(Request $request, PtBooking $ptBooking)
    {
        $this->authorize('update', $ptBooking);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
            'cancel_reason' => 'required_if:status,cancelled|nullable|string',
        ]);

        $ptBooking->update([
            'status' => $validated['status'],
            'cancelled_at' => $validated['status'] === 'cancelled' ? now() : null,
            'cancel_reason' => $validated['cancel_reason'] ?? null,
        ]);

        return redirect()->route('admin.pt-bookings.index')->with('success', 'Booking status updated.');
    }
}
