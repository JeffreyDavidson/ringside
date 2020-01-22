<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class SuspendController extends Controller
{
    /**
     * Suspend a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('suspend', $wrestler);

        if (! $wrestler->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }

        $wrestler->suspend();

        return redirect()->route('wrestlers.index');
    }
}
