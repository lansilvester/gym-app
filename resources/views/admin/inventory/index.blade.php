@extends('layouts.app')
@section('title', 'Inventaris')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Barang Inventaris</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.inventory-categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm">Kategori</a>
            <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambah Barang</a>
        </div>
    </div>

    <form method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, SKU, atau deskripsi..." class="w-full border border-gray-300 rounded-lg px-3 py-2 {{ request('search') ? 'pr-9' : '' }} text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if(request('search'))
                <a href="{{ route('admin.inventory.index', array_filter(['category_id' => request('category_id'), 'status' => request('status')])) }}" class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
        <select name="category_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
            <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Kategori</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Stok</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Harga Beli</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $item->sku }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('admin.inventory.show', $item) }}" class="text-blue-600 hover:underline">{{ $item->name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($item->quantity <= 0)
                            <span class="text-red-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                        @elseif($item->min_stock && $item->quantity <= $item->min_stock)
                            <span class="text-yellow-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                        @else
                            <span class="text-green-600 font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $item->purchase_price ? 'Rp ' . number_format($item->purchase_price, 0, ',', '.') : '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'low_stock' => 'bg-yellow-100 text-yellow-800',
                                'out_of_stock' => 'bg-red-100 text-red-800',
                                'maintenance' => 'bg-blue-100 text-blue-800',
                                'retired' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ str_replace('_', ' ', ucfirst($item->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.inventory.edit', $item) }}" class="text-yellow-600 hover:underline text-xs">Ubah</a>
                        <form method="POST" action="{{ route('admin.inventory.destroy', $item) }}" class="inline ml-2" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada barang inventaris ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $items->withQueryString()->links() }}
</div>
@endsection
