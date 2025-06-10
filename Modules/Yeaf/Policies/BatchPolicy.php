<?php

namespace Modules\Yeaf\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Yeaf\Entities\Batch;

class BatchPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): bool {
        return $user->hasRole(Role::SUPER_ADMIN) || $user->hasRole(Role::YEAF_ADMIN);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): void
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Batch $model): void
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(Role::YEAF_ADMIN) && $user->disabled === false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Batch $model): bool
    {
        return $user->hasRole(Role::YEAF_ADMIN) && $user->disabled === false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Batch $model): void
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Batch $model): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Batch $model): void
    {
        //
    }
}
