<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\MaintenanceLog;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceSchedule::with('inventoryItem', 'assignedTo');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        $schedules = $query->latest('next_due_date')->paginate(15);
        return view('admin.maintenance.index', compact('schedules'));
    }

    public function create()
    {
        $items = InventoryItem::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        return view('admin.maintenance.create', compact('items', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'maintenance_type' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency_days' => 'nullable|integer|min:1',
            'next_due_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:low,medium,high,critical',
            'status' => 'nullable|in:pending,in_progress,overdue,completed',
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';

        MaintenanceSchedule::create($validated);

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance schedule created.');
    }

    public function show(MaintenanceSchedule $schedule)
    {
        $schedule->load('inventoryItem', 'assignedTo');
        $logs = MaintenanceLog::where('maintenance_schedule_id', $schedule->id)
            ->with('performedBy')
            ->latest('performed_at')
            ->get();
        return view('admin.maintenance.show', compact('schedule', 'logs'));
    }

    public function updateStatus(Request $request, MaintenanceSchedule $schedule)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,overdue,completed',
        ]);

        $data = ['status' => $validated['status']];

        if ($validated['status'] === 'completed') {
            $data['last_performed_at'] = now();

            if ($schedule->frequency_days) {
                $data['next_due_date'] = now()->addDays($schedule->frequency_days);
            }
        }

        $schedule->update($data);

        return redirect()->route('admin.maintenance.show', $schedule)->with('success', 'Status updated.');
    }

    public function logMaintenance(Request $request, MaintenanceSchedule $schedule)
    {
        $validated = $request->validate([
            'performed_at' => 'required|date',
            'parts_replaced' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['performed_by'] = auth()->id();
        $validated['maintenance_schedule_id'] = $schedule->id;

        MaintenanceLog::create($validated);

        $schedule->update([
            'last_performed_at' => $validated['performed_at'],
            'next_due_date' => $schedule->frequency_days
                ? \Carbon\Carbon::parse($validated['performed_at'])->addDays($schedule->frequency_days)
                : $schedule->next_due_date,
        ]);

        return redirect()->route('admin.maintenance.show', $schedule)->with('success', 'Maintenance log recorded.');
    }

    public function destroy(MaintenanceSchedule $schedule)
    {
        MaintenanceLog::where('maintenance_schedule_id', $schedule->id)->delete();
        $schedule->delete();
        return redirect()->route('admin.maintenance.index')->with('success', 'Schedule deleted.');
    }
}
