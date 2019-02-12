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
     * Determine whether the user can retire a wrestler.
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
     * Determine whether the user can unretire a retired wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function unretire(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && $wrestler->isRetired();
    }

    /**
     * Determine whether the user can suspend a wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function suspend(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && !$wrestler->isSuspended();
    }

    /**
     * Determine whether the user can reinstate a suspended wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function reinstate(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && $wrestler->isSuspended();
    }

    /**
     * Determine whether the user can injure a wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function injure(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && !$wrestler->isInjured();
    }

    /**
     * Determine whether the user can recover an injured wrestler.
     *
     * @param  \App\User  $user
     * @param  \App\Wrestler  $wrestler
     * @return bool
     */
    public function recover(User $user, Wrestler $wrestler)
    {
        return $user->isAdministrator() && $wrestler->isInjured();
    }
}
