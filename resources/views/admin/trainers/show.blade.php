@extends('layouts.app')
@section('title', 'Detail Pelatih')

@section('content')
<div class="max-w-3xl space-y-4">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.trainers.index') }}" class="text-gray-500 hover:text-gray-700">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800">Pelatih: {{ $trainer->user->name }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Kode Pelatih</label>
                <p class="text-sm text-gray-800">{{ $trainer->trainer_code }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="text-sm text-gray-800">{{ $trainer->user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Telepon</label>
                <p class="text-sm text-gray-800">{{ $trainer->user->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Spesialisasi</label>
                <p class="text-sm text-gray-800">{{ $trainer->specialization ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tarif Per Jam</label>
                <p class="text-sm text-gray-800">Rp {{ number_format($trainer->hourly_rate, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Tersedia</label>
                <p class="text-sm text-gray-800">{{ $trainer->is_available ? 'Ya' : 'Tidak' }}</p>
            </div>
        </div>
        @if($trainer->bio)
        <div>
            <label class="block text-sm font-medium text-gray-500">Bio</label>
            <p class="text-sm text-gray-800">{{ $trainer->bio }}</p>
        </div>
        @endif
        @if($trainer->certifications)
        <div>
            <label class="block text-sm font-medium text-gray-500">Sertifikasi</label>
            <p class="text-sm text-gray-800">{{ $trainer->certifications }}</p>
        </div>
        @endif
    </div>

    <div class="flex gap-2">
        <a href="{{ route('admin.trainers.edit', $trainer) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">Edit</a>
    </div>
</div>
@endsection
