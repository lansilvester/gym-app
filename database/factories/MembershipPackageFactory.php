<?php

namespace Database\Factories;

use App\Models\MembershipPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipPackageFactory extends Factory
{
    protected $model = MembershipPackage::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(),
            'duration_days' => $this->faker->randomElement([30, 90, 180, 365]),
            'price' => $this->faker->randomFloat(2, 100000, 2000000),
            'max_checkin_per_week' => $this->faker->optional(0.5)->numberBetween(3, 7),
            'includes_personal_training' => $this->faker->boolean(30),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
