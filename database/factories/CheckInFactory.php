<?php

namespace Database\Factories;

use App\Models\CheckIn;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckInFactory extends Factory
{
    protected $model = CheckIn::class;

    public function definition(): array
    {
        $checkInAt = $this->faker->dateTimeBetween('-7 days', 'now');

        return [
            'member_id' => Member::factory(),
            'check_in_at' => $checkInAt,
            'check_out_at' => null,
            'method' => $this->faker->randomElement(['manual', 'qr', 'fingerprint']),
            'checked_in_by' => null,
        ];
    }
}
