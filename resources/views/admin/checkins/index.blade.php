@extends('layouts.app')
@section('title', 'Check-ins')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-800">Check-in Management</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Manual check-in form --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold text-gray-800 mb-3">Manual Check-in</h3>
        <form method="POST" action="{{ route('admin.checkins.store') }}" class="flex gap-2 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Member</label>
                <select name="member_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Choose member...</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->user->name }} ({{ $member->member_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                <select name="method" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="manual">Manual</option>
                    <option value="qr">QR Code</option>
                    <option value="fingerprint">Fingerprint</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 whitespace-nowrap">Check In</button>
        </form>
    </div>

    {{-- Today's check-ins --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Today's Check-ins ({{ $checkins->count() }})</h3>
            <span class="text-sm text-gray-500">{{ now()->format('l, d M Y') }}</span>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Member</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Code</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Check In</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Check Out</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Method</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($checkins as $checkin)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $checkin->member->user->name ?? '-' }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $checkin->member->member_code ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $checkin->check_in_at->format('H:i:s') }}</td>
                    <td class="px-4 py-3">
                        @if($checkin->check_out_at)
                            <span>{{ $checkin->check_out_at->format('H:i:s') }}</span>
                        @else
                            <span class="text-yellow-600 font-medium">Still in</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 capitalize">{{ $checkin->method }}</td>
                    <td class="px-4 py-3">
                        @if(!$checkin->check_out_at)
                            <form method="POST" action="{{ route('admin.checkins.checkout', $checkin) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Check Out</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">Completed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No check-ins today</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
