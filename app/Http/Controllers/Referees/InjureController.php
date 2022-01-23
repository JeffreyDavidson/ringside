<?php

namespace App\Http\Controllers\Referees;

use App\Actions\Referees\InjureAction;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class InjureController extends Controller
{
    /**
     * Injure a referee.
     *
     * @param  \App\Models\Referee  $referee
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('injure', $referee);

        throw_unless($referee->canBeInjured(), new CannotBeInjuredException);

        InjureAction::run($referee);

        return redirect()->route('referees.index');
    }
}
