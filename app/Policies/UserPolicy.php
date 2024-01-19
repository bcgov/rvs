<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        $is_admin = false;
        foreach ($user->roles as $role) {
            if (Str::contains($role->name, 'Admin')) {
                $is_admin = true;
                break;
            }
        }

        return $is_admin && $user->disabled === false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function adminUpdate(User $user, User $model): bool
    {
        $is_admin = false;
        foreach ($user->roles as $role) {
            if (Str::contains($role->name, 'Super Admin')) {
                $is_admin = true;
                break;
            }
        }

        return $is_admin && $user->disabled === false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
