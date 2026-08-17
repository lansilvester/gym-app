<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $total = $this->faker->randomFloat(2, 100000, 2000000);

        return [
            'member_id' => Member::factory(),
            'subtotal' => $total,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $total,
            'notes' => $this->faker->optional()->sentence(),
            'due_date' => Carbon::now()->addDays(14),
            'status' => 'draft',
        ];
    }
}
