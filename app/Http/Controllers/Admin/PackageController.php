<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = MembershipPackage::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'max_checkin_per_week' => 'nullable|integer|min:1',
            'includes_personal_training' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        MembershipPackage::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function edit(MembershipPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, MembershipPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'max_checkin_per_week' => 'nullable|integer|min:1',
            'includes_personal_training' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(MembershipPackage $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }
}
