<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\SuspendAction;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class SuspendController extends Controller
{
    /**
     * Suspend a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \App\Actions\Referees\SuspendAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee, SuspendAction $action)
    {
        $this->authorize('suspend', $referee);

        throw_unless($referee->canBeSuspended(), new CannotBeSuspendedException);

        $action->handle($referee);

        return redirect()->route('referees.index');
    }
}
