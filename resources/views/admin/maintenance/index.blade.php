@extends('layouts.app')
@section('title', 'Jadwal Pemeliharaan')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Pemeliharaan Peralatan</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
        </select>
        <select name="priority" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Prioritas</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Kritis</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Peralatan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jatuh Tempo Berikutnya</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Terakhir Dilakukan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Prioritas</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $schedule->inventoryItem->name ?? '-' }}</td>
                    <td class="px-4 py-3 capitalize">{{ $schedule->maintenance_type ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $schedule->next_due_date?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $schedule->last_performed_at?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3 capitalize">{{ $schedule->priority }}</td>
                    <td class="px-4 py-3">
                        @if($schedule->status == 'completed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                        @elseif($schedule->status == 'in_progress')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Dalam Proses</span>
                        @elseif($schedule->status == 'overdue')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Terlambat</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Tertunda</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.maintenance.show', $schedule) }}" class="text-blue-600 hover:underline text-xs">Lihat</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada jadwal ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $schedules->withQueryString()->links() }}
</div>
@endsection
