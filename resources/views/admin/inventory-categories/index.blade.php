@extends('layouts.app')
@section('title', 'Kategori Inventaris')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Kategori Inventaris</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.inventory.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm">Kembali ke Inventaris</a>
            <a href="{{ route('admin.inventory-categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Tambah Kategori</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Deskripsi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $category->description ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $category->items_count }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.inventory-categories.edit', $category) }}" class="text-yellow-600 hover:underline text-xs">Ubah</a>
                        <form method="POST" action="{{ route('admin.inventory-categories.destroy', $category) }}" class="inline ml-2" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada kategori ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}
</div>
@endsection
