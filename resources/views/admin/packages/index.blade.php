@extends('layouts.app')
@section('title', 'Membership Packages')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Membership Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Add Package</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Slug</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Duration</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Price</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Max Check-in</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">PT</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $package->name }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $package->slug }}</td>
                    <td class="px-4 py-3">{{ $package->duration_days }} days</td>
                    <td class="px-4 py-3">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">{{ $package->max_checkin_per_week ?? 'Unlimited' }}</td>
                    <td class="px-4 py-3">
                        @if($package->includes_personal_training)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Yes</span>
                        @else
                            <span class="text-gray-400 text-xs">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($package->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline ml-2" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No packages found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $packages->links() }}
</div>
@endsection
