<?php

namespace Modules\Twp\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Twp\Entities\IndigeneityType;

class IndigeneityTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $ministryRolesToCheck = [Role::TWP_ADMIN, Role::SUPER_ADMIN];
        if($user->roles()->pluck('name')->intersect($ministryRolesToCheck)->isNotEmpty() && $user->disabled === false){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $ministryRolesToCheck = [Role::TWP_ADMIN, Role::SUPER_ADMIN];
        if($user->roles()->pluck('name')->intersect($ministryRolesToCheck)->isNotEmpty() && $user->disabled === false){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IndigeneityType $model): bool
    {
        $ministryRolesToCheck = [Role::TWP_ADMIN, Role::SUPER_ADMIN];
        if($user->roles()->pluck('name')->intersect($ministryRolesToCheck)->isNotEmpty() && $user->disabled === false){
            return true;
        }
        return false;
    }

}
