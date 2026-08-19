@extends('layouts.app')
@section('title', 'Detail Anggota')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">{{ $member->user->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.members.edit', $member) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">Ubah</a>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile card --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-2xl font-bold text-gray-500">{{ substr($member->user->name, 0, 1) }}</div>
                <h2 class="mt-2 font-semibold text-lg">{{ $member->user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $member->member_code }}</p>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Email</span><span>{{ $member->user->email }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Telepon</span><span>{{ $member->user->phone ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Jenis Kelamin</span><span class="capitalize">{{ $member->gender ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">NIK</span><span>{{ $member->nik ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Tanggal Lahir</span><span>{{ $member->birth_date?->format('d M Y') ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Status</span>
                    @if($member->user->is_active)
                        <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            {{-- Active subscription --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Langganan Aktif</h3>
                @if($member->activeSubscription)
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><span class="text-gray-500 block">Paket</span><span class="font-medium">{{ $member->activeSubscription->package->name }}</span></div>
                        <div><span class="text-gray-500 block">Periode</span><span class="font-medium">{{ $member->activeSubscription->start_date->format('d M Y') }} - {{ $member->activeSubscription->end_date->format('d M Y') }}</span></div>
                        <div><span class="text-gray-500 block">Status</span><span class="font-medium capitalize">{{ $member->activeSubscription->status }}</span></div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Tidak ada langganan aktif</p>
                @endif
            </div>

            {{-- Recent check-ins --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Check-in Terkini</h3>
                <div class="space-y-2">
                    @forelse($member->checkIns as $checkin)
                    <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 last:border-0">
                        <span>{{ $checkin->check_in_at->format('d M Y H:i') }}</span>
                        <span class="text-gray-500">{{ $checkin->method }}</span>
                        <span class="text-gray-500">{{ $checkin->check_out_at ? 'Keluar: ' . $checkin->check_out_at->format('H:i') : 'Masih di dalam' }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Tidak ada catatan check-in</p>
                    @endforelse
                </div>
            </div>

            {{-- Medical info --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Informasi Medis</h3>
                @if($member->medicalInfo)
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><span class="text-gray-500 block">Golongan Darah</span><span>{{ $member->medicalInfo->blood_type ?? '-' }}</span></div>
                        <div><span class="text-gray-500 block">Tinggi Badan</span><span>{{ $member->medicalInfo->height_cm ? $member->medicalInfo->height_cm . ' cm' : '-' }}</span></div>
                        <div><span class="text-gray-500 block">Berat Badan</span><span>{{ $member->medicalInfo->weight_kg ? $member->medicalInfo->weight_kg . ' kg' : '-' }}</span></div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">Tidak ada informasi medis yang tercatat</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
