<?php

namespace App\Policies;

use App\Models\MemberSubscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['subscription.view', 'subscription.create', 'subscription.edit']);
    }

    public function view(User $user, MemberSubscription $subscription): bool
    {
        return $user->hasAnyPermission(['subscription.view', 'subscription.edit']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('subscription.create');
    }

    public function update(User $user, MemberSubscription $subscription): bool
    {
        return $user->hasPermissionTo('subscription.edit');
    }

    public function delete(User $user, MemberSubscription $subscription): bool
    {
        return $user->hasPermissionTo('subscription.edit');
    }
}
