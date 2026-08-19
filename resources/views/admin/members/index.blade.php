@extends('layouts.app')
@section('title', 'Anggota')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Anggota</h1>
        <a href="{{ route('admin.members.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambah Anggota</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, email, atau kode..." class="w-full border border-gray-300 rounded-lg px-3 py-2 {{ request('search') ? 'pr-9' : '' }} text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @if(request('search'))
                <a href="{{ route('admin.members.index') }}" class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:text-white hover:bg-gray-400 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </div>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Cari</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Kode</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jenis Kelamin</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($members as $member)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $member->member_code }}</td>
                    <td class="px-4 py-3">{{ $member->user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $member->user->email }}</td>
                    <td class="px-4 py-3 capitalize">{{ $member->gender ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($member->user->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.members.show', $member) }}" class="text-blue-600 hover:underline text-xs">Lihat</a>
                        <a href="{{ route('admin.members.edit', $member) }}" class="text-yellow-600 hover:underline text-xs ml-2">Ubah</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada anggota ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $members->withQueryString()->links() }}
</div>
@endsection
