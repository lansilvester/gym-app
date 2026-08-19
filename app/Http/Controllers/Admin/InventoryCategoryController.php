<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', InventoryCategory::class);

        $categories = InventoryCategory::withCount('items')->orderBy('name')->paginate(15);
        return view('admin.inventory-categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', InventoryCategory::class);

        return view('admin.inventory-categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryCategory::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name',
            'description' => 'nullable|string',
        ]);

        InventoryCategory::create($validated);

        return redirect()->route('admin.inventory-categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(InventoryCategory $inventoryCategory)
    {
        $this->authorize('update', $inventoryCategory);

        return view('admin.inventory-categories.edit', ['category' => $inventoryCategory]);
    }

    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $this->authorize('update', $inventoryCategory);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $inventoryCategory->id,
            'description' => 'nullable|string',
        ]);

        $inventoryCategory->update($validated);

        return redirect()->route('admin.inventory-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(InventoryCategory $inventoryCategory)
    {
        $this->authorize('delete', $inventoryCategory);

        $inventoryCategory->delete();
        return redirect()->route('admin.inventory-categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
