<?php

namespace App\Http\Controllers\Referees;

use App\Models\Referee;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeInjuredException;

class InjureController extends Controller
{
    /**
     * Injure a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('injure', $referee);

        if (! $referee->canBeInjured()) {
            throw new CannotBeInjuredException();
        }

        $referee->injure();

        return redirect()->route('referees.index');
    }
}
