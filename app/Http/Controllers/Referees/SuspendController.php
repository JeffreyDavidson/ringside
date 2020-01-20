<?php

namespace App\Http\Controllers\Referees;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class SuspendController extends Controller
{
    /**
     * Suspend a referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('suspend', $referee);

        if (! $referee->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }

        $referee->suspend();

        return redirect()->route('referees.index');
    }
}
