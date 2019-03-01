<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stable;
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

    /**
     * Determine whether the user can deactivate an active stable.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stable  $stable
     * @return bool
     */
    public function deactivate(User $user, Stable $stable)
    {
        return $user->isAdministrator() && $stable->isActive();
    }

    /**
     * Determine whether the user can activate an inactive stable.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stable  $stable
     * @return bool
     */
    public function activate(User $user, Stable $stable)
    {
        return $user->isAdministrator() && !$stable->isActive();
    }
}
