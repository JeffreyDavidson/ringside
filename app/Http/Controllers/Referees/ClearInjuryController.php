<?php

namespace App\Http\Controllers\Referees;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class ClearInjuryController extends Controller
{
    /**
     * Clear a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('clearFromInjury', $referee);

        if (! $referee->canBeClearedFromInjury()) {
            throw new CannotBeClearedFromInjuryException();
        }

        $referee->clearFromInjury();

        return redirect()->route('referees.index');
    }
}
