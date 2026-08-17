<?php

namespace Tests\Feature;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTransactionTest extends TestCase
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

    public function test_transactions_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.inventory-transactions.index'));
        $response->assertStatus(200);
    }

    public function test_purchase_increases_stock(): void
    {
        $item = InventoryItem::factory()->create(['quantity' => 5]);

        $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'type' => 'purchase',
            'quantity' => 10,
            'notes' => 'Restocking',
        ]);

        $item->refresh();
        $this->assertEquals(15, $item->quantity);
    }

    public function test_usage_decreases_stock(): void
    {
        $item = InventoryItem::factory()->create(['quantity' => 10]);

        $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'type' => 'usage',
            'quantity' => 3,
        ]);

        $item->refresh();
        $this->assertEquals(7, $item->quantity);
    }

    public function test_insufficient_stock_rejected(): void
    {
        $item = InventoryItem::factory()->create(['quantity' => 2]);

        $response = $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'type' => 'usage',
            'quantity' => 5,
        ]);

        $response->assertSessionHasErrors(['quantity']);
        $item->refresh();
        $this->assertEquals(2, $item->quantity);
    }

    public function test_low_stock_status_is_set_when_below_min_stock(): void
    {
        $item = InventoryItem::factory()->create(['quantity' => 10, 'min_stock' => 5, 'status' => 'active']);

        $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'type' => 'usage',
            'quantity' => 7,
        ]);

        $item->refresh();
        $this->assertEquals('low_stock', $item->status);
    }

    public function test_out_of_stock_status_is_set_at_zero(): void
    {
        $item = InventoryItem::factory()->create(['quantity' => 5, 'status' => 'active']);

        $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), [
            'inventory_item_id' => $item->id,
            'type' => 'usage',
            'quantity' => 5,
        ]);

        $item->refresh();
        $this->assertEquals('out_of_stock', $item->status);
    }

    public function test_transaction_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.inventory-transactions.store'), []);
        $response->assertSessionHasErrors(['inventory_item_id', 'type', 'quantity']);
    }
}
