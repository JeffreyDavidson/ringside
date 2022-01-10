<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class UnretireController extends Controller
{
    /**
     * Unretire a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \App\Actions\Referees\UnretireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee, UnretireAction $action)
    {
        $this->authorize('unretire', $referee);

        throw_unless($referee->canBeUnretired(), new CannotBeUnretiredException);

        $action->handle($referee);

        return redirect()->route('referees.index');
    }
}
