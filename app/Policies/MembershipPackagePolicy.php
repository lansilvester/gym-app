<?php

namespace App\Policies;

use App\Models\MembershipPackage;
use App\Models\User;

class MembershipPackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['subscription.view', 'subscription.create']);
    }

    public function view(User $user, MembershipPackage $package): bool
    {
        return $user->hasAnyPermission(['subscription.view', 'subscription.create']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('subscription.create');
    }

    public function update(User $user, MembershipPackage $package): bool
    {
        return $user->hasPermissionTo('subscription.create');
    }

    public function delete(User $user, MembershipPackage $package): bool
    {
        return $user->hasPermissionTo('subscription.create');
    }
}
