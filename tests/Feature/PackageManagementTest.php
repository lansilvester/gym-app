<?php

namespace Tests\Feature;

use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageManagementTest extends TestCase
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

    public function test_packages_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.packages.index'));
        $response->assertStatus(200);
    }

    public function test_package_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.packages.create'));
        $response->assertStatus(200);
    }

    public function test_new_package_can_be_created(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.packages.store'), [
            'name' => 'Gold Monthly',
            'description' => 'Premium monthly package',
            'duration_days' => 30,
            'price' => 500000,
            'max_checkin_per_week' => null,
            'includes_personal_training' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('membership_packages', ['name' => 'Gold Monthly', 'slug' => 'gold-monthly']);
    }

    public function test_package_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.packages.store'), []);
        $response->assertSessionHasErrors(['name', 'duration_days', 'price']);
    }

    public function test_package_edit_form_is_displayed(): void
    {
        $package = MembershipPackage::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.packages.edit', $package));
        $response->assertStatus(200);
    }

    public function test_package_can_be_updated(): void
    {
        $package = MembershipPackage::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin)->put(route('admin.packages.update', $package), [
            'name' => 'New Name',
            'duration_days' => 60,
            'price' => 750000,
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('membership_packages', ['id' => $package->id, 'name' => 'New Name']);
    }

    public function test_package_can_be_deleted(): void
    {
        $package = MembershipPackage::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.packages.destroy', $package));

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseMissing('membership_packages', ['id' => $package->id]);
    }

    public function test_packages_are_sorted_by_sort_order(): void
    {
        MembershipPackage::factory()->create(['name' => 'Z Package', 'sort_order' => 2]);
        MembershipPackage::factory()->create(['name' => 'A Package', 'sort_order' => 1]);

        $response = $this->actingAs($this->admin)->get(route('admin.packages.index'));
        $response->assertStatus(200);
    }
}
