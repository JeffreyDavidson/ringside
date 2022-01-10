<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\EmployAction;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class EmployController extends Controller
{
    /**
     * Employ a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \App\Actions\Referees\EmployAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee, EmployAction $action)
    {
        $this->authorize('employ', $referee);

        throw_unless($referee->canBeEmployed(), new CannotBeEmployedException);

        $action->handle($referee);

        return redirect()->route('referees.index');
    }
}
