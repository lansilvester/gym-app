@extends('layouts.app')
@section('title', 'Payments')

@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold text-gray-800">Payment History</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Payment #</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Invoice #</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Member</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Amount</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Method</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Received By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs font-medium">{{ $payment->payment_number }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $payment->invoice->invoice_number ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $payment->invoice->member->user->name ?? '-' }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">{{ $payment->paymentMethod->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $payment->receivedBy->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->withQueryString()->links() }}
</div>
@endsection
