<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\RetireAction;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class RetireController extends Controller
{
    /**
     * Retire a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \App\Actions\Referees\RetireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee, RetireAction $action)
    {
        $this->authorize('retire', $referee);

        throw_unless($referee->canBeRetired(), new CannotBeRetiredException);

        $action->handle($referee);

        return redirect()->route('referees.index');
    }
}
