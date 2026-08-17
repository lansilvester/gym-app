<?php

namespace Database\Factories;

use App\Models\MaintenanceLog;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    protected $model = MaintenanceLog::class;

    public function definition(): array
    {
        return [
            'maintenance_schedule_id' => MaintenanceSchedule::factory(),
            'performed_at' => Carbon::now(),
            'performed_by' => User::factory(),
            'parts_replaced' => $this->faker->optional()->words(3, true),
            'cost' => $this->faker->optional()->randomFloat(2, 10000, 1000000),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
