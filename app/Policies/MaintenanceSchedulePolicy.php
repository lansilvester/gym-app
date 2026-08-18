<?php

namespace App\Policies;

use App\Models\MaintenanceSchedule;
use App\Models\User;

class MaintenanceSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['inventory.maintenance', 'inventory.view']);
    }

    public function view(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasAnyPermission(['inventory.maintenance', 'inventory.view']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('inventory.maintenance');
    }

    public function update(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasPermissionTo('inventory.maintenance');
    }

    public function delete(User $user, MaintenanceSchedule $schedule): bool
    {
        return $user->hasPermissionTo('inventory.maintenance');
    }
}
