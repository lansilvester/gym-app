@extends('layouts.app')
@section('title', 'Inventory Transactions')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-800">Inventory Transactions</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item name, SKU..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">All Types</option>
            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Item</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">SKU</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Quantity</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Reference</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Notes</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $transaction->inventoryItem->name ?? '-' }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $transaction->inventoryItem->sku ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($transaction->type == 'in')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Stock In</span>
                        @elseif($transaction->type == 'out')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Stock Out</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Adjustment</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium {{ $transaction->type == 'out' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $transaction->type == 'out' ? '-' : '+' }}{{ $transaction->quantity }}
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $transaction->reference_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $transaction->notes ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $transaction->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No transactions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $transactions->withQueryString()->links() }}
</div>
@endsection
