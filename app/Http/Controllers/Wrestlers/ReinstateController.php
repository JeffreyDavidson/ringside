<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ReinstateController extends Controller
{
    /**
     * Reinstate a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('reinstate', $wrestler);

        if (! $wrestler->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }

        $wrestler->reinstate();

        return redirect()->route('wrestlers.index');
    }
}
