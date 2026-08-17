<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryTransactionFactory extends Factory
{
    protected $model = InventoryTransaction::class;

    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'type' => $this->faker->randomElement(['purchase', 'usage', 'adjustment', 'return']),
            'quantity' => $this->faker->numberBetween(1, 10),
            'reference_number' => $this->faker->optional()->numerify('REF-######'),
            'notes' => $this->faker->optional()->sentence(),
            'performed_by' => User::factory(),
        ];
    }
}
