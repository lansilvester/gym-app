@extends('layouts.app')
@section('title', 'Ubah Paket')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Ubah Paket: {{ $package->name }}</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.packages.update', $package) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $package->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $package->slug) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (hari) *</label>
                <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                <input type="number" name="price" value="{{ old('price', $package->price) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maks Check-in per Minggu</label>
                <input type="number" name="max_checkin_per_week" value="{{ old('max_checkin_per_week', $package->max_checkin_per_week) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Kosongkan untuk tidak terbatas">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('description', $package->description) }}</textarea>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <input type="hidden" name="includes_personal_training" value="0">
                <input type="checkbox" name="includes_personal_training" value="1" {{ old('includes_personal_training', $package->includes_personal_training) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label class="text-sm font-medium text-gray-700">Termasuk Personal Training</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label class="text-sm font-medium text-gray-700">Aktif</label>
            </div>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Perbarui Paket</button>
            <a href="{{ route('admin.packages.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
