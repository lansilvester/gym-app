@extends('layouts.app')
@section('title', 'Inventory')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Inventory Items</h1>
        <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Add Item</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item name, SKU..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Categories</option>
            <option value="supplement" {{ request('category') == 'supplement' ? 'selected' : '' }}>Supplements</option>
            <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Equipment</option>
            <option value="apparel" {{ request('category') == 'apparel' ? 'selected' : '' }}>Apparel</option>
            <option value="accessory" {{ request('category') == 'accessory' ? 'selected' : '' }}>Accessories</option>
            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Category</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Stock</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Price</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Cost</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $item->sku }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                    <td class="px-4 py-3 capitalize">{{ $item->category }}</td>
                    <td class="px-4 py-3">
                        @if($item->stock_quantity <= 0)
                            <span class="text-red-600 font-medium">{{ $item->stock_quantity }}</span>
                        @elseif($item->stock_quantity <= $item->minimum_stock)
                            <span class="text-yellow-600 font-medium">{{ $item->stock_quantity }}</span>
                        @else
                            <span class="text-green-600 font-medium">{{ $item->stock_quantity }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($item->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.inventory.edit', $item) }}" class="text-yellow-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.inventory.destroy', $item) }}" class="inline ml-2" onsubmit="return confirm('Delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No inventory items found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $items->withQueryString()->links() }}
</div>
@endsection
