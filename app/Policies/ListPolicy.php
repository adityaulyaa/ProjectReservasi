<?php

namespace App\Policies;

use App\Models\ProjectList;
use App\Models\User;

class ListPolicy
{
    /**
     * Determine whether the user can view any lists.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the list.
     * (List Owner or List Member)
     */
    public function view(User $user, ProjectList $list): bool
    {
        if ($user->id === $list->owner_id) {
            return true;
        }

        return $list->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create lists.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the list.
     * (List Owner only)
     */
    public function update(User $user, ProjectList $list): bool
    {
        return $user->id === $list->owner_id;
    }

    /**
     * Determine whether the user can delete the list.
     * (List Owner only)
     */
    public function delete(User $user, ProjectList $list): bool
    {
        return $user->id === $list->owner_id;
    }

    /**
     * Determine whether the user can add members to the list.
     * (List Owner only)
     */
    public function addMember(User $user, ProjectList $list): bool
    {
        return $user->id === $list->owner_id;
    }

    /**
     * Determine whether the user can remove members from the list.
     * (List Owner only)
     */
    public function removeMember(User $user, ProjectList $list): bool
    {
        return $user->id === $list->owner_id;
    }
}
