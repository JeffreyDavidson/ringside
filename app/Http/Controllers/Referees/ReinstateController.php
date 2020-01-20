<?php

namespace App\Http\Controllers\Referees;

use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class ReinstateController extends Controller
{
    /**
     * Reinstate a referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('reinstate', $referee);

        if (! $referee->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }

        $referee->reinstate();

        return redirect()->route('referees.index');
    }
}
