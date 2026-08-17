<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'member.view', 'member.create', 'member.edit', 'member.delete',
            'subscription.view', 'subscription.create', 'subscription.edit',
            'payment.view', 'payment.create', 'payment.refund',
            'checkin.view', 'checkin.manual_override',
            'trainer.view', 'trainer.schedule', 'trainer.booking',
            'inventory.view', 'inventory.manage', 'inventory.maintenance',
            'report.view', 'report.export',
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::where('name', '!=', 'settings.manage')->get());

        $cashier = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashier->syncPermissions(Permission::whereIn('name', [
            'member.view', 'subscription.view', 'subscription.create',
            'payment.view', 'payment.create', 'checkin.view',
        ])->get());

        $trainer = Role::firstOrCreate(['name' => 'trainer', 'guard_name' => 'web']);
        $trainer->syncPermissions(Permission::whereIn('name', [
            'trainer.view', 'trainer.schedule', 'trainer.booking', 'checkin.view',
        ])->get());

        $member = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
        $member->syncPermissions(Permission::whereIn('name', [
            'checkin.view',
        ])->get());
    }
}
