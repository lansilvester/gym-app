<?php

namespace App\Policies;

use App\Models\InventoryCategory;
use App\Models\User;

class InventoryCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['inventory.view', 'inventory.manage']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }

    public function update(User $user, InventoryCategory $category): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }

    public function delete(User $user, InventoryCategory $category): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }
}
