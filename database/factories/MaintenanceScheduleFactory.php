<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceScheduleFactory extends Factory
{
    protected $model = MaintenanceSchedule::class;

    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'maintenance_type' => $this->faker->randomElement(['preventive', 'corrective', 'calibration', 'inspection']),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'frequency_days' => $this->faker->randomElement([7, 14, 30, 60, 90]),
            'next_due_date' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'assigned_to' => null,
            'status' => 'pending',
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
        ];
    }
}
