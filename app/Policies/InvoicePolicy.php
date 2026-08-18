<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['payment.view', 'payment.create']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->hasAnyPermission(['payment.view', 'payment.create']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('payment.create');
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('payment.create');
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->hasPermissionTo('payment.create');
    }
}
