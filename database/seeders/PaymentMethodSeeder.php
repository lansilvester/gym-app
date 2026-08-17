<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Transfer BCA', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Transfer Mandiri', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Transfer BRI', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'QRIS', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Debit Card', 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Credit Card', 'is_active' => true, 'sort_order' => 7],
            ['name' => 'E-Wallet', 'is_active' => true, 'sort_order' => 8],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['name' => $method['name']], $method);
        }
    }
}
