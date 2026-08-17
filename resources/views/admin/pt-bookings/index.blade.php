@extends('layouts.app')
@section('title', 'PT Bookings')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-800">Personal Training Bookings</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Member</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Trainer</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Time</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $booking->member->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $booking->trainer->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $booking->booking_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                    <td class="px-4 py-3 capitalize">{{ $booking->session_type ?? 'Regular' }}</td>
                    <td class="px-4 py-3">
                        @if($booking->status == 'booked')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Booked</span>
                        @elseif($booking->status == 'confirmed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Confirmed</span>
                        @elseif($booking->status == 'completed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Completed</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.pt-bookings.status.update', $booking) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            @if($booking->status == 'booked')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="text-green-600 hover:underline text-xs">Confirm</button>
                            @elseif($booking->status == 'confirmed')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="text-blue-600 hover:underline text-xs">Complete</button>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No bookings found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $bookings->withQueryString()->links() }}
</div>
@endsection
