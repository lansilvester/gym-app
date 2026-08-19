@extends('layouts.app')
@section('title', 'Tambah Barang Inventaris')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Barang Inventaris</h1>

    <form method="POST" action="{{ route('admin.inventory.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="equipment" {{ old('type') == 'equipment' ? 'selected' : '' }}>Peralatan</option>
                    <option value="supplement" {{ old('type') == 'supplement' ? 'selected' : '' }}>Suplemen</option>
                    <option value="accessory" {{ old('type') == 'accessory' ? 'selected' : '' }}>Aksesori</option>
                    <option value="consumable" {{ old('type') == 'consumable' ? 'selected' : '' }}>Barang Habis Pakai</option>
                    <option value="maintenance_part" {{ old('type') == 'maintenance_part' ? 'selected' : '' }}>Suku Cadang Pemeliharaan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="pcs, kg, box">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Maksimum</label>
                <input type="number" name="max_stock" value="{{ old('max_stock') }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Saat Ini (Rp)</label>
                <input type="number" name="current_value" value="{{ old('current_value') }}" min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="low_stock" {{ old('status') == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                    <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Buat Barang</button>
            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
