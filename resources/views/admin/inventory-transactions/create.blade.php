@extends('layouts.app')
@section('title', 'Catat Transaksi')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Catat Transaksi Inventaris</h1>

    <form method="POST" action="{{ route('admin.inventory-transactions.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang *</label>
                <select name="inventory_item_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih barang...</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} ({{ $item->sku ?? 'no SKU' }}) — Stok: {{ $item->quantity }} {{ $item->unit }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih tipe...</option>
                    <option value="purchase" {{ old('type') == 'purchase' ? 'selected' : '' }}>Pembelian (Stok Masuk)</option>
                    <option value="usage" {{ old('type') == 'usage' ? 'selected' : '' }}>Penggunaan (Stok Keluar)</option>
                    <option value="damaged" {{ old('type') == 'damaged' ? 'selected' : '' }}>Rusak (Stok Keluar)</option>
                    <option value="return" {{ old('type') == 'return' ? 'selected' : '' }}>Pengembalian (Stok Masuk)</option>
                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian (Stok Masuk)</option>
                    <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan (Stok Keluar)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Referensi</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Catat Transaksi</button>
            <a href="{{ route('admin.inventory-transactions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
