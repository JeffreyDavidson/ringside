<?php

namespace App\Policies;

use App\User;
use App\Referee;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefereePolicy
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
     * Determine whether the user can retire a referee.
     *
     * @param  \App\User  $user
     * @param  \App\Referee  $referee
     * @return bool
     */
    public function retire(User $user, Referee $referee)
    {
        return $user->isAdministrator() && ! $referee->isRetired();
    }

    /**
     * Determine whether the user can unretire a retired referee.
     *
     * @param  \App\User  $user
     * @param  \App\Referee  $referee
     * @return bool
     */
    public function unretire(User $user, Referee $referee)
    {
        return $user->isAdministrator() && $referee->isRetired();
    }
}
