@extends('layouts.app')
@section('title', 'Member Details')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ $member->user->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">Edit</a>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-2xl font-bold text-gray-500">{{ substr($member->user->name, 0, 1) }}</div>
                <h2 class="mt-2 font-semibold text-lg">{{ $member->user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $member->member_code }}</p>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Email</span><span>{{ $member->user->email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Phone</span><span>{{ $member->user->phone ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Gender</span><span class="capitalize">{{ $member->gender ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">NIK</span><span>{{ $member->nik ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Birth Date</span><span>{{ $member->birth_date?->format('d M Y') ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span>
                    @if($member->user->is_active)
                        <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            {{-- Active subscription --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Active Subscription</h3>
                @if($member->activeSubscription)
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><span class="text-gray-500 block">Package</span><span class="font-medium">{{ $member->activeSubscription->package->name }}</span></div>
                        <div><span class="text-gray-500 block">Period</span><span class="font-medium">{{ $member->activeSubscription->start_date->format('d M Y') }} - {{ $member->activeSubscription->end_date->format('d M Y') }}</span></div>
                        <div><span class="text-gray-500 block">Status</span><span class="font-medium capitalize">{{ $member->activeSubscription->status }}</span></div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No active subscription</p>
                @endif
            </div>

            {{-- Recent check-ins --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Recent Check-ins</h3>
                <div class="space-y-2">
                    @forelse($member->checkIns as $checkin)
                    <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 last:border-0">
                        <span>{{ $checkin->check_in_at->format('d M Y H:i') }}</span>
                        <span class="text-gray-500">{{ $checkin->method }}</span>
                        <span class="text-gray-500">{{ $checkin->check_out_at ? 'Out: ' . $checkin->check_out_at->format('H:i') : 'Still in' }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No check-in records</p>
                    @endforelse
                </div>
            </div>

            {{-- Medical info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Medical Info</h3>
                @if($member->medicalInfo)
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><span class="text-gray-500 block">Blood Type</span><span>{{ $member->medicalInfo->blood_type ?? '-' }}</span></div>
                        <div><span class="text-gray-500 block">Height</span><span>{{ $member->medicalInfo->height_cm ? $member->medicalInfo->height_cm . ' cm' : '-' }}</span></div>
                        <div><span class="text-gray-500 block">Weight</span><span>{{ $member->medicalInfo->weight_kg ? $member->medicalInfo->weight_kg . ' kg' : '-' }}</span></div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No medical info recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
