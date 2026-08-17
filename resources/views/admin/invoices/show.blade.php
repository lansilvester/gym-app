@extends('layouts.app')
@section('title', 'Invoice Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Invoice {{ $invoice->invoice_number }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Invoice Information</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Invoice #</span><span class="font-mono font-medium">{{ $invoice->invoice_number }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Member</span><span>{{ $invoice->member->user->name ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Created</span><span>{{ $invoice->created_at->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Due Date</span><span>{{ $invoice->due_date?->format('d M Y') ?? '-' }}</span></div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    @if($invoice->status == 'paid')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Paid</span>
                    @elseif($invoice->status == 'partially_paid')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Partial</span>
                    @elseif($invoice->status == 'draft')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Draft</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                    @endif
                </div>
            </div>
            @if($invoice->notes)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-500">Notes:</span>
                    <p class="text-sm mt-1">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Invoice Items</h3>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="text-left py-2 text-gray-600">Description</th>
                            <th class="text-right py-2 text-gray-600">Qty</th>
                            <th class="text-right py-2 text-gray-600">Unit Price</th>
                            <th class="text-right py-2 text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($invoice->items as $item)
                        <tr>
                            <td class="py-2">{{ $item->description }}</td>
                            <td class="py-2 text-right">{{ $item->quantity }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="py-3 text-right font-medium">Total:</td>
                            <td class="py-3 text-right font-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-1 text-right text-gray-500">Paid:</td>
                            <td class="py-1 text-right text-green-600">Rp {{ number_format($invoice->amount_paid, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-1 text-right text-gray-500">Remaining:</td>
                            <td class="py-1 text-right text-red-600 font-medium">Rp {{ number_format($invoice->amount_remaining, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Payments</h3>
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="text-left py-2 text-gray-600">Date</th>
                            <th class="text-left py-2 text-gray-600">Method</th>
                            <th class="text-left py-2 text-gray-600">Reference</th>
                            <th class="text-right py-2 text-gray-600">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($invoice->payments as $payment)
                        <tr>
                            <td class="py-2">{{ $payment->payment_date?->format('d M Y') ?? '-' }}</td>
                            <td class="py-2 capitalize">{{ $payment->paymentMethod->name ?? '-' }}</td>
                            <td class="py-2 text-gray-500">{{ $payment->reference_number ?? '-' }}</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-500">No payments recorded</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
