<?php

namespace App\Policies;

use App\Models\CheckIn;
use App\Models\User;

class CheckInPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['checkin.view', 'checkin.manual_override']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('checkin.manual_override');
    }

    public function update(User $user, CheckIn $checkIn): bool
    {
        return $user->hasPermissionTo('checkin.manual_override');
    }
}
