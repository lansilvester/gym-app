<?php

namespace Tests\Feature;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
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

    public function test_inventory_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.inventory.index'));
        $response->assertStatus(200);
    }

    public function test_inventory_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.inventory.create'));
        $response->assertStatus(200);
    }

    public function test_new_inventory_item_can_be_created(): void
    {
        $category = InventoryCategory::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.inventory.store'), [
            'category_id' => $category->id,
            'sku' => 'EQP-TEST01',
            'name' => 'Test Equipment',
            'type' => 'equipment',
            'quantity' => 5,
            'unit' => 'pcs',
            'min_stock' => 2,
            'purchase_price' => 1000000,
            'location' => 'Floor 1',
        ]);

        $response->assertRedirect(route('admin.inventory.index'));
        $this->assertDatabaseHas('inventory_items', ['sku' => 'EQP-TEST01', 'name' => 'Test Equipment']);
    }

    public function test_inventory_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.inventory.store'), []);
        $response->assertSessionHasErrors(['category_id', 'name']);
    }

    public function test_inventory_item_edit_form_is_displayed(): void
    {
        $item = InventoryItem::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.inventory.edit', $item));
        $response->assertStatus(200);
    }

    public function test_inventory_item_can_be_updated(): void
    {
        $item = InventoryItem::factory()->create(['name' => 'Old Name']);
        $category = InventoryCategory::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.inventory.update', $item), [
            'category_id' => $category->id,
            'name' => 'Updated Name',
            'quantity' => 10,
        ]);

        $response->assertRedirect(route('admin.inventory.show', $item));
        $this->assertDatabaseHas('inventory_items', ['id' => $item->id, 'name' => 'Updated Name']);
    }

    public function test_inventory_item_can_be_deleted(): void
    {
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.inventory.destroy', $item));

        $response->assertRedirect(route('admin.inventory.index'));
        $this->assertDatabaseMissing('inventory_items', ['id' => $item->id]);
    }

    public function test_inventory_can_be_filtered_by_category(): void
    {
        $category = InventoryCategory::factory()->create();
        InventoryItem::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.inventory.index', ['category_id' => $category->id]));
        $response->assertStatus(200);
    }

    public function test_inventory_can_be_searched(): void
    {
        InventoryItem::factory()->create(['name' => 'Searchable Equipment', 'sku' => 'SRC-001']);

        $response = $this->actingAs($this->admin)->get(route('admin.inventory.index', ['search' => 'Searchable']));
        $response->assertStatus(200);
    }
}
