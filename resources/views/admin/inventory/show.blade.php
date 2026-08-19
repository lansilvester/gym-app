@extends('layouts.app')
@section('title', $item->name)

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ $item->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.inventory.edit', $item) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-sm">Ubah</a>
            <a href="{{ route('admin.inventory.index') }}" class="text-gray-600 hover:text-gray-800 text-sm">&larr; Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4 space-y-2 text-sm">
            <h2 class="font-semibold text-gray-700">Detail Barang</h2>
            <div class="flex justify-between"><span class="text-gray-500">SKU</span><span class="font-mono">{{ $item->sku ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Kategori</span><span>{{ $item->category->name ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Tipe</span><span class="capitalize">{{ str_replace('_', ' ', $item->type) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Lokasi</span><span>{{ $item->location ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Deskripsi</span><span>{{ $item->description ?? '-' }}</span></div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 space-y-2 text-sm">
            <h2 class="font-semibold text-gray-700">Stok dan Harga</h2>
            <div class="flex justify-between"><span class="text-gray-500">Jumlah</span>
                @if($item->quantity <= 0)
                    <span class="text-red-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                @elseif($item->min_stock && $item->quantity <= $item->min_stock)
                    <span class="text-yellow-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                @else
                    <span class="text-green-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                @endif
            </div>
            <div class="flex justify-between"><span class="text-gray-500">Stok Minimum</span><span>{{ $item->min_stock ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Stok Maksimum</span><span>{{ $item->max_stock ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Harga Beli</span><span>{{ $item->purchase_price ? 'Rp ' . number_format($item->purchase_price, 0, ',', '.') : '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Nilai Saat Ini</span><span>{{ $item->current_value ? 'Rp ' . number_format($item->current_value, 0, ',', '.') : '-' }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Status</span>
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-800',
                        'low_stock' => 'bg-yellow-100 text-yellow-800',
                        'out_of_stock' => 'bg-red-100 text-red-800',
                        'maintenance' => 'bg-blue-100 text-blue-800',
                        'retired' => 'bg-gray-100 text-gray-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$item->status] ?? '' }}">
                    {{ str_replace('_', ' ', ucfirst($item->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="font-semibold text-gray-700">Transaksi Terakhir</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tipe</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jumlah</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Referensi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Catatan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($item->transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $transaction->created_at->format('d M Y H:i') }}</td>
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
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
