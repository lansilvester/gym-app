<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\CheckIn;
use App\Models\Invoice;
use App\Models\InventoryItem;
use App\Models\MaintenanceSchedule;
use App\Models\MemberSubscription;
use App\Models\Payment;
use App\Models\PtBooking;
use App\Models\MembershipPackage;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $now = Carbon::now();

        $stats = [
            'total_members'        => Member::count(),
            'active_subscriptions' => MemberSubscription::where('status', 'active')->count(),
            'today_checkins'       => CheckIn::today()->count(),
            'revenue_this_month'   => Payment::whereMonth('payment_date', $now->month)
                                    ->whereYear('payment_date', $now->year)
                                    ->sum('amount'),
            'pending_invoices'     => Invoice::whereIn('status', ['draft', 'unpaid'])->count(),
            'low_stock_items'      => InventoryItem::where('status', 'low_stock')->count(),
            'overdue_maintenance'  => MaintenanceSchedule::where('status', 'overdue')->count(),
            'new_this_week'        => Member::where('created_at', '>=', $now->copy()->startOfWeek())->count(),
            'revenue_last_month'   => Payment::whereMonth('payment_date', $now->copy()->subMonth()->month)
                                    ->whereYear('payment_date', $now->copy()->subMonth()->year)
                                    ->sum('amount'),
            'total_revenue'        => Payment::sum('amount'),
        ];

        // Revenue trend last 6 months
        $revenueTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $revenueTrend->push([
                'month'   => $month->format('M'),
                'revenue' => (float) Payment::whereMonth('payment_date', $month->month)
                                    ->whereYear('payment_date', $month->year)
                                    ->sum('amount'),
            ]);
        }
        $maxRevenue = max([...$revenueTrend->pluck('revenue')->toArray(), 1]);

        // Check-in last 7 days
        $checkinTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $checkinTrend->push([
                'day'   => $day->format('D'),
                'count' => CheckIn::whereDate('check_in_at', $day)->count(),
            ]);
        }
        $maxCheckin = max([...$checkinTrend->pluck('count')->toArray(), 1]);

        // Membership breakdown
        $membershipBreakdown = MemberSubscription::where('status', 'active')
            ->join('membership_packages', 'member_subscriptions.package_id', '=', 'membership_packages.id')
            ->select('membership_packages.name', \DB::raw('count(*) as count'))
            ->groupBy('membership_packages.name')
            ->get();

        $recent_checkins = CheckIn::with('member.user')
            ->latest('check_in_at')
            ->limit(8)
            ->get();

        $upcoming_bookings = PtBooking::with('member.user', 'trainer.user')
            ->where('booking_date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->limit(6)
            ->get();

        $latest_members = Member::with('user', 'activeSubscription.package')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats', 'recent_checkins', 'upcoming_bookings', 'latest_members',
            'revenueTrend', 'maxRevenue', 'checkinTrend', 'maxCheckin',
            'membershipBreakdown'
        ));
    }
}
