<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipPackage;

class MemberPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'Basic Monthly', 'duration_days' => 30, 'price' => 250000, 'max_checkin_per_week' => 3, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 1, 'description' => 'Basic gym access 3x per week'],
            ['name' => 'Standard Monthly', 'duration_days' => 30, 'price' => 350000, 'max_checkin_per_week' => null, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 2, 'description' => 'Unlimited gym access'],
            ['name' => 'Premium Monthly', 'duration_days' => 30, 'price' => 550000, 'max_checkin_per_week' => null, 'includes_personal_training' => true, 'is_active' => true, 'sort_order' => 3, 'description' => 'Unlimited access + 4 PT sessions'],
            ['name' => 'Quarterly Basic', 'duration_days' => 90, 'price' => 650000, 'max_checkin_per_week' => 3, 'includes_personal_training' => false, 'is_active' => true, 'sort_order' => 4, 'description' => '3 months basic access'],
            ['name' => 'Quarterly Premium', 'duration_days' => 90, 'price' => 1400000, 'max_checkin_per_week' => null, 'includes_personal_training' => true, 'is_active' => true, 'sort_order' => 5, 'description' => '3 months unlimited + 12 PT sessions'],
            ['name' => 'Annual Premium', 'duration_days' => 365, 'price' => 5000000, 'max_checkin_per_week' => null, 'includes_personal_training' => true, 'is_active' => true, 'sort_order' => 6, 'description' => '1 year unlimited + 48 PT sessions'],
        ];

        foreach ($packages as $pkg) {
            MembershipPackage::firstOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}
