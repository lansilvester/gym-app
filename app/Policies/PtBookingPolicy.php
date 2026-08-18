<?php

namespace App\Policies;

use App\Models\PtBooking;
use App\Models\User;

class PtBookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['trainer.booking', 'trainer.view']);
    }

    public function view(User $user, PtBooking $ptBooking): bool
    {
        return $user->hasAnyPermission(['trainer.booking', 'trainer.view']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('trainer.booking');
    }

    public function update(User $user, PtBooking $ptBooking): bool
    {
        return $user->hasPermissionTo('trainer.booking');
    }
}
