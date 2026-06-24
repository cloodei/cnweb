<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

class GroupPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Group $group): bool
    {
        return $group->hasMember($user);
    }

    public function update(User $user, Group $group): bool
    {
        return $group->roleFor($user) === Group::ROLE_OWNER;
    }

    public function delete(User $user, Group $group): bool
    {
        return $group->roleFor($user) === Group::ROLE_OWNER;
    }

    public function manageInvites(User $user, Group $group): bool
    {
        return $group->roleFor($user) === Group::ROLE_OWNER;
    }

    public function createItinerary(User $user, Group $group): bool
    {
        return $group->canEditItineraries($user);
    }

    public function manageDestinations(User $user, Group $group): bool
    {
        return $group->canEditItineraries($user);
    }
}
