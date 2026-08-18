<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['member.view', 'member.create', 'member.edit', 'member.delete']);
    }

    public function view(User $user, Member $member): bool
    {
        return $user->hasAnyPermission(['member.view', 'member.edit']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('member.create');
    }

    public function update(User $user, Member $member): bool
    {
        return $user->hasPermissionTo('member.edit');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasPermissionTo('member.delete');
    }
}
