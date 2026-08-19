@extends('layouts.app')
@section('title', 'Transaksi Inventaris')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Transaksi Inventaris</h1>
        <a href="{{ route('admin.inventory-transactions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Catat Transaksi</a>
    </div>

    <form method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama barang, SKU, atau referensi..." class="w-full border border-gray-300 rounded-lg px-3 py-2 {{ request('search') ? 'pr-9' : '' }} text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if(request('search'))
                <a href="{{ route('admin.inventory-transactions.index', array_filter(['type' => request('type'), 'date_from' => request('date_from'), 'date_to' => request('date_to')])) }}" class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
        <select name="type" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Tipe</option>
            <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Pembelian</option>
            <option value="usage" {{ request('type') == 'usage' ? 'selected' : '' }}>Penggunaan</option>
            <option value="damaged" {{ request('type') == 'damaged' ? 'selected' : '' }}>Rusak</option>
            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
            <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Pengembalian</option>
            <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jumlah</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Referensi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Catatan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $transaction->inventoryItem->name ?? '-' }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $transaction->inventoryItem->sku ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $typeColors = [
                                'purchase' => 'bg-green-100 text-green-800',
                                'return' => 'bg-green-100 text-green-800',
                                'usage' => 'bg-red-100 text-red-800',
                                'damaged' => 'bg-red-100 text-red-800',
                                'maintenance' => 'bg-orange-100 text-orange-800',
                                'adjustment' => 'bg-blue-100 text-blue-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeColors[$transaction->type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-medium {{ in_array($transaction->type, ['purchase', 'return', 'adjustment']) ? 'text-green-600' : 'text-red-600' }}">
                        {{ in_array($transaction->type, ['purchase', 'return', 'adjustment']) ? '+' : '-' }}{{ $transaction->quantity }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $transaction->reference_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $transaction->notes ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $transaction->performedBy->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $transactions->withQueryString()->links() }}
</div>
@endsection
