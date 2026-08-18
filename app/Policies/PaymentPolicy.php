<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['payment.view', 'payment.create']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->hasAnyPermission(['payment.view', 'payment.create']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('payment.create');
    }
}
