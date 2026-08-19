@extends('layouts.app')
@section('title', 'Tambah Pelatih')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Pelatih Baru</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.trainers.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi *</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi *</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keahlian</label>
                <input type="text" name="speciality" value="{{ old('speciality') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="contoh: Latihan Beban, Kardio">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Bio / Catatan</label>
            <textarea name="bio" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('bio') }}</textarea>
        </div>

        <div class="flex gap-2 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700">Buat Pelatih</button>
            <a href="{{ route('admin.trainers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300">Batal</a>
        </div>
    </form>
</div>
@endsection
