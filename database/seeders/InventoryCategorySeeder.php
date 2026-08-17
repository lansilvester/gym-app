<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;

class InventoryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Cardio Equipment', 'Strength Equipment', 'Free Weights', 'Accessories', 'Supplements', 'Maintenance Parts', 'Consumables'];
        foreach ($categories as $name) {
            InventoryCategory::firstOrCreate(['name' => $name]);
        }
    }
}
