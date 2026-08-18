<?php

namespace App\Policies;

use App\Models\InventoryTransaction;
use App\Models\User;

class InventoryTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['inventory.view', 'inventory.manage']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('inventory.manage');
    }
}
