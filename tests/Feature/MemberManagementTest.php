<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MemberManagementTest extends TestCase
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

    public function test_members_index_page_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));
        $response->assertStatus(200);
    }

    public function test_members_can_be_listed(): void
    {
        Member::factory()->count(3)->create();
        $response = $this->actingAs($this->admin)->get(route('admin.members.index'));
        $response->assertStatus(200);
    }

    public function test_member_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.members.create'));
        $response->assertStatus(200);
    }

    public function test_new_member_can_be_created(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.members.store'), [
            'name' => 'Test Member',
            'email' => 'testmember@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'gender' => 'male',
            'birth_date' => '1995-05-15',
        ]);

        $response->assertRedirect(route('admin.members.index'));
        $this->assertDatabaseHas('users', ['email' => 'testmember@example.com']);
        $this->assertDatabaseHas('members', ['gender' => 'male']);
    }

    public function test_member_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.members.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_member_creation_validates_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@email.com']);

        $response = $this->actingAs($this->admin)->post(route('admin.members.store'), [
            'name' => 'Test',
            'email' => 'existing@email.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_member_show_page_is_displayed(): void
    {
        $member = Member::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.members.show', $member));
        $response->assertStatus(200);
    }

    public function test_member_edit_form_is_displayed(): void
    {
        $member = Member::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.members.edit', $member));
        $response->assertStatus(200);
    }

    public function test_member_can_be_updated(): void
    {
        $member = Member::factory()->create();
        $user = $member->user;

        $response = $this->actingAs($this->admin)->put(route('admin.members.update', $member), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'gender' => 'female',
        ]);

        $response->assertRedirect(route('admin.members.show', $member));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
        $this->assertDatabaseHas('members', ['id' => $member->id, 'gender' => 'female']);
    }

    public function test_member_can_be_deleted(): void
    {
        $member = Member::factory()->create();
        $userId = $member->user_id;

        $response = $this->actingAs($this->admin)->delete(route('admin.members.destroy', $member));

        $response->assertRedirect(route('admin.members.index'));
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_members_can_be_searched(): void
    {
        $member = Member::factory()->create();
        $member->user->update(['name' => 'Searchable Name']);

        $response = $this->actingAs($this->admin)->get(route('admin.members.index', ['search' => 'Searchable']));
        $response->assertStatus(200);
    }
}
