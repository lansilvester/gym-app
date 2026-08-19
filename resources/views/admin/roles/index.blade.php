@extends('layouts.app')
@section('title', 'Role dan Izin')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Role dan Izin</h1>
        <a href="{{ route('admin.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambah Role</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Nama Tampilan</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Deskripsi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jumlah Pengguna</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Izin</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $role->name }}</td>
                    <td class="px-4 py-3 font-medium">{{ $role->display_name ?? $role->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $role->description ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $role->users_count ?? $role->users()->count() }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($role->permissions->take(3) as $permission)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">{{ $permission->name }}</span>
                            @endforeach
                            @if($role->permissions->count() > 3)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">+{{ $role->permissions->count() - 3 }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-yellow-600 hover:underline text-xs">Ubah</a>
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline ml-2" onsubmit="return confirm('Hapus role ini? Pengguna dengan role ini akan kehilangan izinnya.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada role ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
