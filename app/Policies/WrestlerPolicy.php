<?php

namespace App\Policies;

use App\User;
use App\Wrestler;
use Illuminate\Auth\Access\HandlesAuthorization;

class WrestlerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create wrestlers.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can update a wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function update(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can delete a wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function delete(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can restore a deleted wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function restore(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can create a retirement for wrestlers.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function retire(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && !$wrestler->isRetired();
    }

    /**
     * Determine whether the user can unretire a wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function unretire(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && $wrestler->isRetired();
    }
}
