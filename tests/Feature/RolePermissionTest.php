<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
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

    public function test_roles_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.roles.index'));
        $response->assertStatus(200);
    }

    public function test_role_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.roles.create'));
        $response->assertStatus(200);
    }

    public function test_new_role_can_be_created(): void
    {
        $perm1 = Permission::firstOrCreate(['name' => 'test.view', 'guard_name' => 'web']);
        $perm2 = Permission::firstOrCreate(['name' => 'test.create', 'guard_name' => 'web']);

        $response = $this->actingAs($this->admin)->post(route('admin.roles.store'), [
            'name' => 'test_manager',
            'permissions' => [$perm1->id, $perm2->id],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'test_manager']);

        $role = Role::where('name', 'test_manager')->first();
        $this->assertCount(2, $role->permissions);
    }

    public function test_role_creation_validates_unique_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.roles.store'), [
            'name' => 'super_admin',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_role_edit_form_is_displayed(): void
    {
        $role = Role::where('name', 'admin')->first();
        $response = $this->actingAs($this->admin)->get(route('admin.roles.edit', $role));
        $response->assertStatus(200);
    }

    public function test_role_can_be_updated(): void
    {
        $role = Role::firstOrCreate(['name' => 'old_role', 'guard_name' => 'web']);

        $response = $this->actingAs($this->admin)->put(route('admin.roles.update', $role), [
            'name' => 'new_role',
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'new_role']);
    }

    public function test_role_permissions_can_be_synced(): void
    {
        $role = Role::firstOrCreate(['name' => 'sync_test', 'guard_name' => 'web']);
        $perm = Permission::firstOrCreate(['name' => 'sync.test', 'guard_name' => 'web']);

        $this->actingAs($this->admin)->put(route('admin.roles.update', $role), [
            'name' => 'sync_test',
            'permissions' => [$perm->id],
        ]);

        $role->refresh();
        $this->assertCount(1, $role->permissions);
        $this->assertTrue($role->hasPermissionTo('sync.test'));
    }

    public function test_role_can_be_deleted(): void
    {
        $role = Role::firstOrCreate(['name' => 'deletable_role', 'guard_name' => 'web']);

        $response = $this->actingAs($this->admin)->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_seeded_roles_exist(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'super_admin']);
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'cashier']);
        $this->assertDatabaseHas('roles', ['name' => 'trainer']);
        $this->assertDatabaseHas('roles', ['name' => 'member']);
    }

    public function test_seeded_permissions_exist(): void
    {
        $this->assertDatabaseHas('permissions', ['name' => 'member.view']);
        $this->assertDatabaseHas('permissions', ['name' => 'payment.create']);
        $this->assertDatabaseHas('permissions', ['name' => 'inventory.manage']);
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $role = Role::where('name', 'super_admin')->first();
        $this->assertEquals(Permission::count(), $role->permissions->count());
    }
}
