<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryCategoryFactory extends Factory
{
    protected $model = InventoryCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Cardio Equipment', 'Strength Equipment', 'Accessories', 'Cleaning Supplies', 'Office Supplies']),
            'description' => $this->faker->sentence(),
        ];
    }
}
