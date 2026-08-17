<?php

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        $user = User::factory()->create(['is_active' => true]);

        return [
            'user_id' => $user->id,
            'trainer_code' => 'TRN-' . strtoupper(substr(uniqid(), -6)),
            'specialization' => $this->faker->randomElement(['Cardio', 'Strength', 'Yoga', 'HIIT', 'CrossFit']),
            'certifications' => $this->faker->sentence(),
            'hourly_rate' => $this->faker->randomFloat(2, 100000, 500000),
            'bio' => $this->faker->paragraph(),
            'is_available' => true,
        ];
    }
}
