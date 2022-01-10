<?php

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\ClearInjuryAction;
use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ClearInjuryController extends Controller
{
    /**
     * Have a wrestler recover from an injury.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \App\Actions\Wrestlers\ClearInjuryAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler, ClearInjuryAction $action)
    {
        $this->authorize('clearFromInjury', $wrestler);

        throw_unless($wrestler->canBeClearedFromInjury(), new CannotBeClearedFromInjuryException);

        $action->handle($wrestler);

        return redirect()->route('wrestlers.index');
    }
}
