@extends('layouts.app')
@section('title', 'Ubah Barang Inventaris')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Ubah Barang: {{ $item->name }}</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inventory.update', $item) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $item->sku) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Pilih...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                <input type="text" name="type" value="{{ old('type', $item->type) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah *</label>
                <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                <input type="number" name="min_stock" value="{{ old('min_stock', $item->min_stock) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Maksimum</label>
                <input type="number" name="max_stock" value="{{ old('max_stock', $item->max_stock) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                <input type="number" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price) }}" min="0" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $item->location) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="low_stock" {{ old('status', $item->status) == 'low_stock' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="out_of_stock" {{ old('status', $item->status) == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                    <option value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                    <option value="retired" {{ old('status', $item->status) == 'retired' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Perbarui Barang</button>
            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
