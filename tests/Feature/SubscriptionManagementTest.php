<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\MembershipPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionManagementTest extends TestCase
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

    public function test_subscriptions_index_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.subscriptions.index'));
        $response->assertStatus(200);
    }

    public function test_subscription_create_form_is_displayed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.subscriptions.create'));
        $response->assertStatus(200);
    }

    public function test_new_subscription_can_be_created(): void
    {
        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create(['duration_days' => 30]);

        $response = $this->actingAs($this->admin)->post(route('admin.subscriptions.store'), [
            'member_id' => $member->id,
            'package_id' => $package->id,
            'start_date' => Carbon::now()->format('Y-m-d'),
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.subscriptions.index'));
        $this->assertDatabaseHas('member_subscriptions', [
            'member_id' => $member->id,
            'package_id' => $package->id,
            'status' => 'active',
        ]);
    }

    public function test_subscription_end_date_is_calculated_automatically(): void
    {
        $member = Member::factory()->create();
        $package = MembershipPackage::factory()->create(['duration_days' => 90]);
        $startDate = Carbon::now();

        $this->actingAs($this->admin)->post(route('admin.subscriptions.store'), [
            'member_id' => $member->id,
            'package_id' => $package->id,
            'start_date' => $startDate->format('Y-m-d'),
            'status' => 'active',
        ]);

        $subscription = MemberSubscription::where('member_id', $member->id)->first();
        $this->assertEquals($startDate->copy()->addDays(90)->format('Y-m-d'), $subscription->end_date->format('Y-m-d'));
    }

    public function test_subscription_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.subscriptions.store'), []);
        $response->assertSessionHasErrors(['member_id', 'package_id', 'start_date']);
    }

    public function test_subscription_can_be_updated(): void
    {
        $subscription = MemberSubscription::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)->put(route('admin.subscriptions.update', $subscription), [
            'member_id' => $subscription->member_id,
            'package_id' => $subscription->package_id,
            'start_date' => $subscription->start_date->format('Y-m-d'),
            'end_date' => $subscription->end_date->format('Y-m-d'),
            'status' => 'expired',
        ]);

        $response->assertRedirect(route('admin.subscriptions.index'));
        $this->assertDatabaseHas('member_subscriptions', ['id' => $subscription->id, 'status' => 'expired']);
    }

    public function test_subscription_can_be_deleted(): void
    {
        $subscription = MemberSubscription::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.subscriptions.destroy', $subscription));

        $response->assertRedirect(route('admin.subscriptions.index'));
        $this->assertDatabaseMissing('member_subscriptions', ['id' => $subscription->id]);
    }

    public function test_subscriptions_can_be_filtered_by_status(): void
    {
        MemberSubscription::factory()->create(['status' => 'active']);
        MemberSubscription::factory()->create(['status' => 'expired']);

        $response = $this->actingAs($this->admin)->get(route('admin.subscriptions.index', ['status' => 'active']));
        $response->assertStatus(200);
    }
}
