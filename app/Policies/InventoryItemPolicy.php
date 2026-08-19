<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['inventory.view', 'inventory.manage', 'inventory.maintenance']);
    }

    public function view(User $user, InventoryItem $inventory): bool
    {
        return $user->hasAnyPermission(['inventory.view', 'inventory.manage', 'inventory.maintenance']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }

    public function update(User $user, InventoryItem $inventory): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }

    public function delete(User $user, InventoryItem $inventory): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }
}
