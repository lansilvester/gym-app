@extends('layouts.app')
@section('title', 'Trainers')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Trainers</h1>
        <a href="{{ route('admin.trainers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Add Trainer</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Phone</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Speciality</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($trainers as $trainer)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $trainer->user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $trainer->user->email }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $trainer->user->phone ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $trainer->speciality ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($trainer->user->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.trainers.edit', $trainer) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" class="inline ml-2" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No trainers found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $trainers->links() }}
</div>
@endsection
