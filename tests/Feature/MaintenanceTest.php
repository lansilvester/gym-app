<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole('super_admin');
    }

    public function test_maintenance_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.maintenance.index'));
        $response->assertStatus(200);
    }

    public function test_maintenance_schedule_can_be_created(): void
    {
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.maintenance.store'), [
            'inventory_item_id' => $item->id,
            'title' => 'Monthly Treadmill Service',
            'maintenance_type' => 'preventive',
            'frequency_days' => 30,
            'next_due_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'priority' => 'medium',
        ]);

        $response->assertRedirect(route('admin.maintenance.index'));
        $this->assertDatabaseHas('maintenance_schedules', [
            'title' => 'Monthly Treadmill Service',
            'status' => 'pending',
        ]);
    }

    public function test_maintenance_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.maintenance.store'), []);
        $response->assertSessionHasErrors(['inventory_item_id', 'title', 'next_due_date']);
    }

    public function test_maintenance_status_can_be_updated_to_in_progress(): void
    {
        $schedule = MaintenanceSchedule::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)->patch(route('admin.maintenance.status.update', $schedule), [
            'status' => 'in_progress',
        ]);

        $this->assertDatabaseHas('maintenance_schedules', ['id' => $schedule->id, 'status' => 'in_progress']);
    }

    public function test_maintenance_completion_updates_next_due_date(): void
    {
        $schedule = MaintenanceSchedule::factory()->create([
            'status' => 'in_progress',
            'frequency_days' => 30,
            'next_due_date' => Carbon::now(),
        ]);

        $this->actingAs($this->admin)->patch(route('admin.maintenance.status.update', $schedule), [
            'status' => 'completed',
        ]);

        $schedule->refresh();
        $this->assertEquals('completed', $schedule->status);
        $this->assertNotNull($schedule->last_performed_at);
        $this->assertTrue($schedule->next_due_date->isAfter(now()->subDays(29)));
    }

    public function test_maintenance_log_can_be_recorded(): void
    {
        $schedule = MaintenanceSchedule::factory()->create(['frequency_days' => 30]);

        $response = $this->actingAs($this->admin)->post(route('admin.maintenance.log', $schedule), [
            'performed_at' => Carbon::now()->format('Y-m-d'),
            'parts_replaced' => 'Belt, Bearings',
            'cost' => 500000,
            'notes' => 'Full service completed',
        ]);

        $response->assertRedirect(route('admin.maintenance.show', $schedule));
        $this->assertDatabaseHas('maintenance_logs', [
            'maintenance_schedule_id' => $schedule->id,
            'cost' => 500000,
            'performed_by' => $this->admin->id,
        ]);
    }

    public function test_maintenance_can_be_filtered_by_status(): void
    {
        MaintenanceSchedule::factory()->create(['status' => 'pending']);
        MaintenanceSchedule::factory()->create(['status' => 'overdue']);

        $response = $this->actingAs($this->admin)->get(route('admin.maintenance.index', ['status' => 'pending']));
        $response->assertStatus(200);
    }

    public function test_maintenance_can_be_filtered_by_priority(): void
    {
        MaintenanceSchedule::factory()->create(['priority' => 'high']);

        $response = $this->actingAs($this->admin)->get(route('admin.maintenance.index', ['priority' => 'high']));
        $response->assertStatus(200);
    }
}
