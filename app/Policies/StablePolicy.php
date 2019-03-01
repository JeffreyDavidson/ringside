<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StablePolicy
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
}
