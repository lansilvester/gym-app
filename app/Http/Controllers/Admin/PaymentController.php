<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $query = Payment::with('invoice.member.user', 'paymentMethod', 'receivedBy');

        if ($status = $request->input('status')) {
            $query->whereHas('invoice', fn($q) => $q->where('status', $status));
        }

        if ($methodId = $request->input('payment_method_id')) {
            $query->where('payment_method_id', $methodId);
        }

        $payments = $query->latest('payment_date')->paginate(15);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('admin.payments.index', compact('payments', 'paymentMethods'));
    }

    public function create()
    {
        $this->authorize('create', Payment::class);

        $invoices = Invoice::whereIn('status', ['draft', 'unpaid', 'partially_paid'])
            ->with('member.user')
            ->latest()
            ->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('admin.payments.create', compact('invoices', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Payment::class);

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['received_by'] = auth()->id();

        $payment = Payment::create($validated);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $totalPaid = $invoice->payments()->sum('amount');

        if ($totalPaid >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partially_paid']);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load('invoice.member.user', 'paymentMethod', 'receivedBy', 'refunds');
        return view('admin.payments.show', compact('payment'));
    }
}
