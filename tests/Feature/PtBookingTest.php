<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\PtBooking;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtBookingTest extends TestCase
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

    public function test_pt_bookings_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pt-bookings.index'));
        $response->assertStatus(200);
    }

    public function test_pt_booking_can_be_created(): void
    {
        $member = Member::factory()->create();
        $trainer = Trainer::factory()->create(['is_available' => true]);

        $response = $this->actingAs($this->admin)->post(route('admin.pt-bookings.store'), [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'booking_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'session_type' => 'Strength Training',
        ]);

        $response->assertRedirect(route('admin.pt-bookings.index'));
        $this->assertDatabaseHas('pt_bookings', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'status' => 'booked',
        ]);
    }

    public function test_pt_booking_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pt-bookings.store'), []);
        $response->assertSessionHasErrors(['member_id', 'trainer_id', 'booking_date', 'start_time', 'end_time']);
    }

    public function test_booking_status_can_be_confirmed(): void
    {
        $booking = PtBooking::factory()->create(['status' => 'booked']);

        $response = $this->actingAs($this->admin)->patch(route('admin.pt-bookings.status.update', $booking), [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect(route('admin.pt-bookings.index'));
        $this->assertDatabaseHas('pt_bookings', ['id' => $booking->id, 'status' => 'confirmed']);
    }

    public function test_booking_status_can_be_completed(): void
    {
        $booking = PtBooking::factory()->create(['status' => 'confirmed']);

        $response = $this->actingAs($this->admin)->patch(route('admin.pt-bookings.status.update', $booking), [
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('pt_bookings', ['id' => $booking->id, 'status' => 'completed']);
    }

    public function test_booking_can_be_cancelled(): void
    {
        $booking = PtBooking::factory()->create(['status' => 'confirmed']);

        $response = $this->actingAs($this->admin)->patch(route('admin.pt-bookings.status.update', $booking), [
            'status' => 'cancelled',
            'cancel_reason' => 'Member request',
        ]);

        $this->assertDatabaseHas('pt_bookings', ['id' => $booking->id, 'status' => 'cancelled', 'cancel_reason' => 'Member request']);
    }

    public function test_bookings_can_be_filtered_by_status(): void
    {
        PtBooking::factory()->create(['status' => 'confirmed']);
        PtBooking::factory()->create(['status' => 'cancelled']);

        $response = $this->actingAs($this->admin)->get(route('admin.pt-bookings.index', ['status' => 'confirmed']));
        $response->assertStatus(200);
    }

    public function test_bookings_can_be_filtered_by_date(): void
    {
        PtBooking::factory()->create(['booking_date' => Carbon::today()]);

        $response = $this->actingAs($this->admin)->get(route('admin.pt-bookings.index', ['date' => Carbon::today()->format('Y-m-d')]));
        $response->assertStatus(200);
    }
}
