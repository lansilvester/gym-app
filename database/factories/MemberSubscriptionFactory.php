<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\MembershipPackage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberSubscriptionFactory extends Factory
{
    protected $model = MemberSubscription::class;

    public function definition(): array
    {
        $startDate = Carbon::now()->subDays(rand(0, 30));
        $duration = $this->faker->randomElement([30, 90, 180, 365]);

        return [
            'member_id' => Member::factory(),
            'package_id' => MembershipPackage::factory(),
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addDays($duration),
            'status' => 'active',
            'auto_renew' => false,
            'remaining_PT_sessions' => 0,
        ];
    }
}
