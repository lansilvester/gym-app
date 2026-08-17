@extends('layouts.app')
@section('title', $schedule->title)

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ $schedule->title }}</h1>
        <a href="{{ route('admin.maintenance.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">&larr; Back</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 space-y-2 text-sm">
            <h2 class="font-semibold text-gray-700">Schedule Details</h2>
            <div class="flex justify-between"><span class="text-gray-500">Equipment</span><span>{{ $schedule->inventoryItem->name ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Type</span><span class="capitalize">{{ $schedule->maintenance_type ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Priority</span><span class="capitalize">{{ $schedule->priority }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Frequency</span><span>{{ $schedule->frequency_days ? $schedule->frequency_days . ' days' : 'One-time' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Next Due</span><span>{{ $schedule->next_due_date?->format('d M Y') ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Last Performed</span><span>{{ $schedule->last_performed_at?->format('d M Y H:i') ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Assigned To</span><span>{{ $schedule->assignedTo->name ?? '-' }}</span></div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 space-y-3 text-sm">
            <h2 class="font-semibold text-gray-700">Update Status</h2>
            <div class="flex items-center gap-2">
                <span class="text-gray-500">Current:</span>
                @if($schedule->status == 'completed')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Completed</span>
                @elseif($schedule->status == 'in_progress')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                @elseif($schedule->status == 'overdue')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                @endif
            </div>
            @if($schedule->status !== 'completed')
            <form method="POST" action="{{ route('admin.maintenance.status.update', $schedule) }}" class="flex gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1">
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="overdue">Overdue</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Update</button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 space-y-3 text-sm">
        <h2 class="font-semibold text-gray-700">Log Maintenance</h2>
        <form method="POST" action="{{ route('admin.maintenance.log', $schedule) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            @csrf
            <input type="date" name="performed_at" value="{{ now()->format('Y-m-d') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            <input type="text" name="parts_replaced" placeholder="Parts replaced" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <input type="number" name="cost" placeholder="Cost" step="0.01" min="0" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">Log</button>
        </form>
        <input type="text" name="notes_display" id="notes_display" placeholder="Notes (set via JS)" class="hidden">
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Performed By</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Parts Replaced</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Cost</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $log->performed_at?->format('d M Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $log->performedBy->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $log->parts_replaced ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $log->cost ? number_format($log->cost, 0, ',', '.') : '-' }}</td>
                    <td class="px-4 py-3">{{ $log->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No maintenance logs yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
