<?php

namespace Database\Factories;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        return [
            'category_id' => InventoryCategory::factory(),
            'sku' => 'EQP-' . strtoupper(substr(uniqid(), -6)),
            'name' => $this->faker->words(2, true),
            'type' => $this->faker->randomElement(['equipment', 'consumable', 'accessory']),
            'quantity' => $this->faker->numberBetween(1, 20),
            'unit' => $this->faker->randomElement(['pcs', 'kg', 'liter', 'box']),
            'min_stock' => $this->faker->numberBetween(1, 5),
            'max_stock' => $this->faker->numberBetween(10, 50),
            'purchase_price' => $this->faker->randomFloat(2, 10000, 5000000),
            'location' => $this->faker->randomElement(['Floor 1', 'Floor 2', 'Storage Room', 'Main Hall']),
            'status' => 'active',
        ];
    }
}
