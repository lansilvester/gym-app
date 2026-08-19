@extends('layouts.app')
@section('title', 'Dasbor')
@section('page-title', 'Dasbor')
@section('page-subtitle', 'Selamat datang kembali! Berikut yang terjadi di gym Anda hari ini.')

@section('content')
<div class="space-y-6">

    {{-- Primary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5">
        <div class="stat-card animate-fade-in-up delay-100" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Anggota</p>
                    <p class="text-3xl font-extrabold text-white mt-1">{{ $stats['total_members'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-4">
                <span class="badge badge-success text-[10px] py-0.5">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +{{ $stats['new_this_week'] }}
                </span>
                <span class="text-indigo-200 text-xs">baru minggu ini</span>
            </div>
        </div>

        <div class="stat-card animate-fade-in-up delay-200" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Langganan Aktif</p>
                    <p class="text-3xl font-extrabold text-white mt-1">{{ $stats['active_subscriptions'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="progress-bar">
                    <div class="progress-bar-fill bg-white/40" style="width: {{ $stats['total_members'] > 0 ? round(($stats['active_subscriptions'] / $stats['total_members']) * 100) : 0 }}%"></div>
                </div>
                <p class="text-emerald-200 text-xs mt-1.5">{{ $stats['total_members'] > 0 ? round(($stats['active_subscriptions'] / $stats['total_members']) * 100) : 0 }}% tingkat konversi</p>
            </div>
        </div>

        <div class="stat-card animate-fade-in-up delay-300" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Check-in Hari Ini</p>
                    <p class="text-3xl font-extrabold text-white mt-1">{{ $stats['today_checkins'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-4">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                <span class="text-amber-200 text-xs font-medium">Pelacakan langsung</span>
            </div>
        </div>

        <div class="stat-card animate-fade-in-up delay-400" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-extrabold text-white mt-1">Rp {{ number_format($stats['revenue_this_month'], 0, ',', '.') }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            @php
                $revenueChange = $stats['revenue_last_month'] > 0
                    ? round((($stats['revenue_this_month'] - $stats['revenue_last_month']) / $stats['revenue_last_month']) * 100)
                    : ($stats['revenue_this_month'] > 0 ? 100 : 0);
            @endphp
            <div class="flex items-center gap-1.5 mt-4">
                @if($revenueChange >= 0)
                <span class="badge text-[10px] py-0.5" style="background: rgba(255,255,255,0.2); color: white;">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +{{ $revenueChange }}%
                </span>
                @else
                <span class="badge text-[10px] py-0.5" style="background: rgba(255,255,255,0.2); color: white;">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    {{ $revenueChange }}%
                </span>
                @endif
                <span class="text-purple-200 text-xs">dibandingkan bulan lalu</span>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 animate-fade-in-up delay-500 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-medium uppercase tracking-wide">Tagihan Tertunda</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['pending_invoices'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 animate-fade-in-up delay-500 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-medium uppercase tracking-wide">Barang Stok Menipis</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['low_stock_items'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 animate-fade-in-up delay-600 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-medium uppercase tracking-wide">Pemeliharaan Terlambat</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['overdue_maintenance'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 animate-fade-in-up delay-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Ikhtisar Pendapatan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">6 bulan terakhir</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400">Semua Waktu</p>
                    <p class="text-sm font-bold text-slate-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="flex items-end gap-3 h-48">
                @foreach($revenueTrend as $i => $item)
                @php
                    $height = $maxRevenue > 0 ? round(($item['revenue'] / $maxRevenue) * 100) : 0;
                    $colors = ['#6366f1', '#818cf8', '#a78bfa', '#c4b5fd', '#818cf8', '#6366f1'];
                    $color = $colors[$i % count($colors)];
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group">
                    <div class="w-full relative" style="height: {{ max($height, 4) }}%">
                        <div class="chart-bar absolute inset-0 rounded-t-lg opacity-80 group-hover:opacity-100 transition-opacity"
                             style="background: linear-gradient(180deg, {{ $color }} 0%, {{ $color }}aa 100%); animation-delay: {{ $i * 0.1 }}s;">
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-[10px] font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $item['month'] }}</div>
                        <div class="text-[9px] text-slate-400">Rp {{ $item['revenue'] >= 1000000 ? number_format($item['revenue'] / 1000000, 1) . 'M' : number_format($item['revenue'] / 1000, 0) . 'K' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 animate-fade-in-up delay-400">
            <h3 class="text-base font-bold text-slate-800 mb-1">Paket Keanggotaan</h3>
            <p class="text-xs text-slate-400 mb-5">Distribusi langganan aktif</p>
            @if($membershipBreakdown->isEmpty())
                <div class="flex items-center justify-center h-32 text-slate-400 text-sm">Tidak ada langganan aktif</div>
            @else
                @php
                    $totalSubs = $membershipBreakdown->sum('count');
                    $barColors = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500', 'bg-cyan-500', 'bg-purple-500'];
                @endphp
                <div class="space-y-4">
                    @foreach($membershipBreakdown as $i => $item)
                    @php
                        $pct = $totalSubs > 0 ? round(($item['count'] / $totalSubs) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-slate-700">{{ $item['name'] }}</span>
                            <span class="text-xs font-bold text-slate-600">{{ $item['count'] }} <span class="font-normal text-slate-400">({{ $pct }}%)</span></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-bar-fill {{ $barColors[$i % count($barColors)] }}" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Check-in Trend + Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 animate-fade-in-up delay-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Aktivitas Check-in</h3>
                    <p class="text-xs text-slate-400 mt-0.5">7 hari terakhir</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="flex items-end gap-3 h-40">
                @foreach($checkinTrend as $i => $item)
                @php
                    $height = $maxCheckin > 0 ? round(($item['count'] / $maxCheckin) * 100) : 0;
                    $isToday = $i === 6;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group">
                    <div class="w-full relative" style="height: {{ max($height, 4) }}%">
                        <div class="chart-bar absolute inset-0 rounded-t-lg transition-opacity {{ $isToday ? 'opacity-100' : 'opacity-60 group-hover:opacity-100' }}"
                             style="background: {{ $isToday ? 'linear-gradient(180deg, #0ea5e9 0%, #38bdf8 100%)' : 'linear-gradient(180deg, #cbd5e1 0%, #e2e8f0 100%)' }}; animation-delay: {{ $i * 0.1 }}s;">
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-[10px] font-semibold {{ $isToday ? 'text-sky-600' : 'text-slate-600' }} transition-colors">{{ $item['day'] }}</div>
                        <div class="text-[9px] text-slate-400">{{ $item['count'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 animate-fade-in-up delay-500">
            <h3 class="text-base font-bold text-slate-800 mb-1">Aksi Cepat</h3>
            <p class="text-xs text-slate-400 mb-5">Pintasan yang sering digunakan</p>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.checkins.index') }}" class="quick-action">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-semibold">Check-in</span>
                </a>
                <a href="{{ route('admin.members.create') }}" class="quick-action">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span class="text-xs font-semibold">Tambah Anggota</span>
                </a>
                <a href="{{ route('admin.invoices.create') }}" class="quick-action">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span class="text-xs font-semibold">Tagihan Baru</span>
                </a>
                <a href="{{ route('admin.pt-bookings.index') }}" class="quick-action">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-semibold">Booking PT</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom Row: Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 animate-fade-in-up delay-500">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Check-in Terbaru</h3>
                </div>
                <a href="{{ route('admin.checkins.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recent_checkins as $checkin)
                <div class="px-6 py-3.5 flex items-center justify-between table-row-hover">
                    <div class="flex items-center gap-3">
                        <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                            {{ substr($checkin->member->user->name ?? 'N', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $checkin->member->user->name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-slate-400 font-mono">{{ $checkin->member->member_code ?? '' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">{{ $checkin->check_in_at->format('H:i') }}</p>
                        <span class="badge badge-slate text-[10px]">{{ $checkin->method }}</span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-slate-400">Tidak ada check-in hari ini</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 animate-fade-in-up delay-600">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Booking PT Mendatang</h3>
                </div>
                <a href="{{ route('admin.pt-bookings.index') }}" class="text-xs font-semibold text-purple-600 hover:text-purple-700 transition">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($upcoming_bookings as $booking)
                <div class="px-6 py-3.5 flex items-center justify-between table-row-hover">
                    <div class="flex items-center gap-3">
                        <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #a855f7, #ec4899);">
                            {{ substr($booking->member->user->name ?? 'M', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $booking->member->user->name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-slate-400">bersama {{ $booking->trainer->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">{{ $booking->booking_date->format('d M') }}</p>
                        <p class="text-[11px] text-slate-400">{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                        @php
                            $statusClass = match($booking->status) {
                                'confirmed' => 'badge-success',
                                'booked'    => 'badge-warning',
                                'completed' => 'badge-info',
                                'cancelled' => 'badge-danger',
                                'no_show'   => 'badge-slate',
                                default     => 'badge-slate',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} text-[10px] mt-1">{{ $booking->status }}</span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <p class="text-sm text-slate-400">Tidak ada booking mendatang</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Latest Members --}}
    @if($latest_members->count())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 animate-fade-in-up delay-600">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Anggota Terbaru</h3>
            </div>
            <a href="{{ route('admin.members.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-3 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($latest_members as $member)
                    <tr class="table-row-hover">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="avatar avatar-sm" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                                    {{ substr($member->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $member->user->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $member->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-500">{{ $member->member_code }}</td>
                        <td class="px-6 py-3.5">
                            @if($member->activeSubscription)
                                <span class="badge badge-success text-[10px]">{{ $member->activeSubscription->package->name ?? 'N/A' }}</span>
                            @else
                                <span class="badge badge-slate text-[10px]">Tidak ada paket</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-xs text-slate-500">{{ $member->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
