<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can create new users.
     * Super Admins are the only users who can physically create new users.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update a given user model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isSuperAdmin() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete a given user model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
