<?php

namespace Tests\Feature;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerManagementTest extends TestCase
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

    public function test_trainers_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.trainers.index'));
        $response->assertStatus(200);
    }

    public function test_trainer_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.trainers.create'));
        $response->assertStatus(200);
    }

    public function test_new_trainer_can_be_created(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.trainers.store'), [
            'name' => 'New Trainer',
            'email' => 'newtrainer@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'specialization' => 'Cardio',
            'hourly_rate' => 200000,
        ]);

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertDatabaseHas('users', ['email' => 'newtrainer@example.com']);
        $this->assertDatabaseHas('trainers', ['specialization' => 'Cardio']);
    }

    public function test_trainer_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.trainers.store'), []);
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_trainer_show_page_is_displayed(): void
    {
        $trainer = Trainer::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.trainers.show', $trainer));
        $response->assertStatus(200);
    }

    public function test_trainer_edit_form_is_displayed(): void
    {
        $trainer = Trainer::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.trainers.edit', $trainer));
        $response->assertStatus(200);
    }

    public function test_trainer_can_be_updated(): void
    {
        $trainer = Trainer::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.trainers.update', $trainer), [
            'name' => 'Updated Trainer',
            'email' => $trainer->user->email,
            'specialization' => 'Strength',
            'hourly_rate' => 250000,
        ]);

        $response->assertRedirect(route('admin.trainers.show', $trainer));
        $this->assertDatabaseHas('trainers', ['id' => $trainer->id, 'specialization' => 'Strength']);
    }

    public function test_trainer_can_be_deleted(): void
    {
        $trainer = Trainer::factory()->create();
        $userId = $trainer->user_id;

        $response = $this->actingAs($this->admin)->delete(route('admin.trainers.destroy', $trainer));

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_new_trainer_gets_trainer_role(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        $this->actingAs($this->admin)->post(route('admin.trainers.store'), [
            'name' => 'Trainer Role Test',
            'email' => 'trainerrole@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'trainerrole@example.com')->first();
        $this->assertTrue($user->hasRole('trainer'));
    }

    public function test_trainers_can_be_searched(): void
    {
        $trainer = Trainer::factory()->create();
        $trainer->user->update(['name' => 'Searchable Trainer']);

        $response = $this->actingAs($this->admin)->get(route('admin.trainers.index', ['search' => 'Searchable']));
        $response->assertStatus(200);
    }
}
