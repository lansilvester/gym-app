<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\Trainer;
use App\Models\MembershipPackage;
use App\Models\MemberSubscription;
use App\Models\CheckIn;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InventoryItem;
use App\Models\MaintenanceSchedule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gym.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // Trainer
        $trainerUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'trainer@gym.com',
            'password' => 'password',
            'is_active' => true,
        ]);
        $trainerUser->assignRole('trainer');
        Trainer::create([
            'user_id' => $trainerUser->id,
            'trainer_code' => 'TRN-' . strtoupper(Str::random(6)),
            'specialization' => 'Strength & Conditioning',
            'certifications' => 'NSCA-CPT, ACE Personal Trainer',
            'hourly_rate' => 150000,
            'bio' => 'Certified personal trainer with 5 years experience',
        ]);

        // Members
        $memberNames = [
            ['name' => 'Andi Wijaya', 'email' => 'andi@email.com', 'gender' => 'male'],
            ['name' => 'Sari Dewi', 'email' => 'sari@email.com', 'gender' => 'female'],
            ['name' => 'Rizki Pratama', 'email' => 'rizki@email.com', 'gender' => 'male'],
        ];

        $packages = MembershipPackage::all();

        foreach ($memberNames as $i => $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => 'password',
                'phone' => '0812' . rand(10000000, 99999999),
                'is_active' => true,
            ]);
            $user->assignRole('member');

            $member = Member::create([
                'user_id' => $user->id,
                'member_code' => 'MBR-' . strtoupper(Str::random(8)),
                'gender' => $data['gender'],
                'birth_date' => Carbon::now()->subYears(rand(20, 35))->subDays(rand(0, 365)),
                'address' => 'Jl. Contoh No. ' . ($i + 1) . ', Jakarta',
                'emergency_contact_name' => 'Emergency Contact ' . ($i + 1),
                'emergency_contact_phone' => '0811' . rand(10000000, 99999999),
            ]);

            // Active subscription
            $pkg = $packages->random();
            MemberSubscription::create([
                'member_id' => $member->id,
                'package_id' => $pkg->id,
                'start_date' => Carbon::now()->subDays(rand(1, 15)),
                'end_date' => Carbon::now()->addDays($pkg->duration_days - rand(1, 15)),
                'status' => 'active',
                'remaining_PT_sessions' => $pkg->includes_personal_training ? rand(1, 4) : 0,
            ]);

            // Some check-ins
            for ($j = 0; $j < rand(2, 5); $j++) {
                CheckIn::create([
                    'member_id' => $member->id,
                    'check_in_at' => Carbon::now()->subDays($j)->addHours(rand(7, 18)),
                    'check_out_at' => Carbon::now()->subDays($j)->addHours(rand(8, 20)),
                    'method' => ['qr', 'nfc', 'manual'][rand(0, 2)],
                ]);
            }
        }

        // Sample inventory items
        InventoryItem::create([
            'category_id' => 1,
            'sku' => 'EQP-001',
            'name' => 'Treadmill ProForm 5000',
            'type' => 'equipment',
            'quantity' => 3,
            'unit' => 'pcs',
            'min_stock' => 1,
            'purchase_price' => 15000000,
            'current_value' => 12000000,
            'location' => 'Cardio Zone',
            'status' => 'active',
        ]);

        InventoryItem::create([
            'category_id' => 6,
            'sku' => 'MNT-001',
            'name' => 'Treadmill Belt Lubricant',
            'type' => 'consumable',
            'quantity' => 2,
            'unit' => 'bottle',
            'min_stock' => 5,
            'purchase_price' => 75000,
            'location' => 'Storage Room',
            'status' => 'low_stock',
        ]);

        MaintenanceSchedule::create([
            'inventory_item_id' => 1,
            'maintenance_type' => 'preventive',
            'title' => 'Monthly Treadmill Service',
            'description' => 'Belt inspection, lubrication, motor check',
            'frequency_days' => 30,
            'next_due_date' => Carbon::now()->addDays(5),
            'assigned_to' => $admin->id,
            'status' => 'pending',
            'priority' => 'medium',
        ]);
    }
}
