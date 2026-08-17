<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'member_code' => 'MBR-' . strtoupper(substr(uniqid(), -8)),
            'nik' => $this->faker->numerify('################'),
            'birth_date' => $this->faker->dateTimeBetween('-40 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->numerify('08##########'),
        ];
    }
}
