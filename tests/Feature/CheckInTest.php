<?php

namespace Tests\Feature;

use App\Models\CheckIn;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckInTest extends TestCase
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

    public function test_checkins_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.checkins.index'));
        $response->assertStatus(200);
    }

    public function test_member_can_check_in_by_member_id(): void
    {
        $member = Member::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('admin.checkins.store'), [
            'member_id' => $member->id,
            'method' => 'manual',
        ]);

        $response->assertRedirect(route('admin.checkins.index'));
        $this->assertDatabaseHas('check_ins', [
            'member_id' => $member->id,
            'method' => 'manual',
            'checked_in_by' => $this->admin->id,
        ]);
    }

    public function test_member_can_check_in_by_member_code(): void
    {
        $member = Member::factory()->create(['member_code' => 'MBR-TEST001']);

        $response = $this->actingAs($this->admin)->post(route('admin.checkins.store'), [
            'member_code' => 'MBR-TEST001',
            'method' => 'qr',
        ]);

        $response->assertRedirect(route('admin.checkins.index'));
        $this->assertDatabaseHas('check_ins', ['member_id' => $member->id]);
    }

    public function test_checkin_validates_member_exists(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.checkins.store'), [
            'member_id' => 99999,
        ]);

        $response->assertSessionHasErrors(['member_id']);
    }

    public function test_member_can_check_out(): void
    {
        $checkIn = CheckIn::factory()->create([
            'check_in_at' => now()->subHours(2),
            'check_out_at' => null,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.checkins.checkout', $checkIn));

        $response->assertRedirect(route('admin.checkins.index'));
        $checkIn->refresh();
        $this->assertNotNull($checkIn->check_out_at);
    }

    public function test_already_checked_out_member_cannot_checkout_again(): void
    {
        $checkIn = CheckIn::factory()->create([
            'check_out_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.checkins.checkout', $checkIn));

        $response->assertSessionHas('error');
    }

    public function test_checkins_can_be_searched(): void
    {
        $member = Member::factory()->create();
        $member->user->update(['name' => 'Searchable Person']);
        CheckIn::factory()->create(['member_id' => $member->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.checkins.index', ['search' => 'Searchable']));
        $response->assertStatus(200);
    }

    public function test_checkins_can_be_filtered_by_date(): void
    {
        CheckIn::factory()->create(['check_in_at' => now()->subDays(3)]);

        $response = $this->actingAs($this->admin)->get(route('admin.checkins.index', ['date' => now()->subDays(3)->format('Y-m-d')]));
        $response->assertStatus(200);
    }
}
