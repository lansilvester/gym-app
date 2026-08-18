<?php

namespace App\Policies;

use App\Models\Trainer;
use App\Models\User;

class TrainerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['trainer.view', 'trainer.schedule', 'trainer.booking']);
    }

    public function view(User $user, Trainer $trainer): bool
    {
        return $user->hasAnyPermission(['trainer.view', 'trainer.schedule', 'trainer.booking']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('trainer.schedule');
    }

    public function update(User $user, Trainer $trainer): bool
    {
        return $user->hasPermissionTo('trainer.schedule');
    }

    public function delete(User $user, Trainer $trainer): bool
    {
        return $user->hasPermissionTo('trainer.schedule');
    }
}
