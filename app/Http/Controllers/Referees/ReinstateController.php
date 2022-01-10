<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class ReinstateController extends Controller
{
    /**
     * Reinstate a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \App\Actions\Referees\ReinstateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee, ReinstateAction $action)
    {
        $this->authorize('reinstate', $referee);

        throw_unless($referee->canBeReinstated(), new CannotBeReinstatedException);

        $action->handle($referee);

        return redirect()->route('referees.index');
    }
}
