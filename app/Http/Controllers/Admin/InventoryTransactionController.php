<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', InventoryTransaction::class);

        $query = InventoryTransaction::with('inventoryItem', 'performedBy');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($itemId = $request->input('inventory_item_id')) {
            $query->where('inventory_item_id', $itemId);
        }

        if ($search = $request->input('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $search);
            $query->whereHas('inventoryItem', fn($q) => $q->where('name', 'like', "{$search}%")
                ->orWhere('sku', 'like', "{$search}%"))
                ->orWhere('reference_number', 'like', "{$search}%");
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $transactions = $query->latest()->paginate(20);
        $items = InventoryItem::orderBy('name')->get();
        return view('admin.inventory-transactions.index', compact('transactions', 'items'));
    }

    public function create()
    {
        $this->authorize('create', InventoryItem::class);

        $items = InventoryItem::orderBy('name')->get();
        return view('admin.inventory-transactions.create', compact('items'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryItem::class);

        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'type' => 'required|in:purchase,usage,damaged,adjustment,return,maintenance',
            'quantity' => 'required|integer',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['performed_by'] = auth()->id();

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        $quantity = $validated['quantity'];

        if (in_array($validated['type'], ['usage', 'damaged', 'maintenance']) && $item->quantity < abs($quantity)) {
            return back()->withErrors(['quantity' => 'Insufficient stock. Current stock: ' . $item->quantity])->withInput();
        }

        DB::transaction(function () use ($item, $quantity, $validated) {
            if (in_array($validated['type'], ['purchase', 'return', 'adjustment'])) {
                $item->increment('quantity', abs($quantity));
            } elseif (in_array($validated['type'], ['usage', 'damaged', 'maintenance'])) {
                $item->decrement('quantity', abs($quantity));
            }

            $item->refresh();

            if ($item->quantity === 0) {
                $item->update(['status' => 'out_of_stock']);
            } elseif ($item->min_stock && $item->quantity <= $item->min_stock) {
                $item->update(['status' => 'low_stock']);
            } elseif ($item->status === 'low_stock' || $item->status === 'out_of_stock') {
                $item->update(['status' => 'active']);
            }

            InventoryTransaction::create($validated);
        });

        return redirect()->route('admin.inventory-transactions.index')->with('success', 'Transaksi berhasil dicatat.');
    }
}
