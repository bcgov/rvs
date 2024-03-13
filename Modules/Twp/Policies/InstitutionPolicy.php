<?php

namespace Modules\Twp\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Twp\Entities\Institution;
use Barryvdh\Debugbar\Facades\Debugbar;

class InstitutionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $rolesToCheck = [Role::SUPER_ADMIN, Role::TWP_ADMIN];
        return $user->roles()->pluck('name')->intersect($rolesToCheck)->isNotEmpty() && $user->disabled === false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Institution $model): bool
    {
        $rolesToCheck = [Role::SUPER_ADMIN, Role::TWP_ADMIN];
        return $user->roles()->pluck('name')->intersect($rolesToCheck)->isNotEmpty() && $user->disabled === false;
    }

}
