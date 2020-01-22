<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class InjureController extends Controller
{
    /**
     * Injure a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('injure', $wrestler);

        if (! $wrestler->canBeInjured()) {
            throw new CannotBeInjuredException();
        }

        $wrestler->injure();

        return redirect()->route('wrestlers.index');
    }
}
