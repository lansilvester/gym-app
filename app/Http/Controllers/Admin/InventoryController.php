<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', InventoryItem::class);

        $query = InventoryItem::with('category');

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        $items = $query->latest()->paginate(15);
        $categories = InventoryCategory::orderBy('name')->get();
        return view('admin.inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', InventoryItem::class);

        $categories = InventoryCategory::orderBy('name')->get();
        return view('admin.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryItem::class);

        $validated = $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'sku' => 'nullable|string|unique:inventory_items,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:in_stock,low_stock,out_of_stock,discontinued',
        ]);

        InventoryItem::create($validated);

        return redirect()->route('admin.inventory.index')->with('success', 'Item created successfully.');
    }

    public function show(InventoryItem $inventory)
    {
        $this->authorize('view', $inventory);

        $inventory->load(['category', 'transactions' => fn($q) => $q->with('performedBy')->latest()->limit(20)]);
        return view('admin.inventory.show', ['item' => $inventory]);
    }

    public function edit(InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $categories = InventoryCategory::orderBy('name')->get();
        return view('admin.inventory.edit', ['item' => $inventory, 'categories' => $categories]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'sku' => 'nullable|string|unique:inventory_items,sku,' . $inventory->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:in_stock,low_stock,out_of_stock,discontinued',
        ]);

        $inventory->update($validated);

        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Item updated successfully.');
    }

    public function destroy(InventoryItem $inventory)
    {
        $this->authorize('delete', $inventory);

        $inventory->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Item deleted.');
    }
}
