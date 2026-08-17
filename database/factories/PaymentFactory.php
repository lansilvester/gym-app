<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'amount' => $this->faker->randomFloat(2, 50000, 2000000),
            'payment_date' => Carbon::now(),
            'reference_number' => $this->faker->optional()->numerify('REF-######'),
            'notes' => $this->faker->optional()->sentence(),
            'received_by' => User::factory(),
        ];
    }
}
