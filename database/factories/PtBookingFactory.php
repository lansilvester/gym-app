<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\PtBooking;
use App\Models\Trainer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PtBookingFactory extends Factory
{
    protected $model = PtBooking::class;

    public function definition(): array
    {
        $startTime = $this->faker->randomElement(['08:00', '09:00', '10:00', '14:00', '15:00', '16:00']);
        $startHour = (int) explode(':', $startTime)[0];

        return [
            'member_id' => Member::factory(),
            'trainer_id' => Trainer::factory(),
            'booking_date' => Carbon::tomorrow(),
            'start_time' => $startTime,
            'end_time' => sprintf('%02d:00', $startHour + 1),
            'status' => 'booked',
            'session_type' => $this->faker->randomElement(['Strength Training', 'Cardio', 'HIIT', 'Yoga', 'CrossFit']),
        ];
    }
}
