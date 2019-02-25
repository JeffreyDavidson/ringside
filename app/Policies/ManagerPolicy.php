<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManagerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create managers.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a manager.
     *
     * @param  App\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->isAdministrator();
    }
}
