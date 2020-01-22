<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ClearInjuryController extends Controller
{
    /**
     * Clear a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('clearFromInjury', $wrestler);

        if (! $wrestler->canBeClearedFromInjury()) {
            throw new CannotBeClearedFromInjuryException();
        }

        $wrestler->clearFromInjury();

        return redirect()->route('wrestlers.index');
    }
}
