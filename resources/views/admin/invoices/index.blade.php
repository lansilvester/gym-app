@extends('layouts.app')
@section('title', 'Faktur')

@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Faktur</h1>
        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">+ Buat Faktur</a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. faktur, anggota..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
            <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Sebagian Dibayar</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Filter</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">No. Faktur</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Anggota</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Jumlah</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Dibayar</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $invoice->member->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($invoice->amount_paid, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($invoice->status == 'paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Lunas</span>
                        @elseif($invoice->status == 'partially_paid')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Sebagian</span>
                        @elseif($invoice->status == 'voided')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Dibatalkan</span>
                        @elseif($invoice->status == 'draft')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Draf</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $invoice->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-blue-600 hover:underline text-xs">Lihat</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada faktur ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $invoices->withQueryString()->links() }}
</div>
@endsection
