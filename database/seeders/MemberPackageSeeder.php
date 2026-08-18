<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPackage;

class MemberPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'Visit', 'duration_days' => 1, 'price' => 50000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 1, 'description' => 'Single visit gym access'],
            ['name' => '1 Month', 'duration_days' => 30, 'price' => 270000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 2, 'description' => 'Unlimited gym access for 1 month'],
            ['name' => '3 Month', 'duration_days' => 90, 'price' => 250000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 3, 'description' => 'Unlimited gym access for 3 months'],
            ['name' => '6 Month', 'duration_days' => 180, 'price' => 240000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 4, 'description' => 'Unlimited gym access for 6 months'],
            ['name' => '1 Year', 'duration_days' => 365, 'price' => 220000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 5, 'description' => 'Unlimited gym access for 1 year'],
        ];

        foreach ($packages as $pkg) {
            MembershipPackage::firstOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}
