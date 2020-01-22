<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class UnretireController extends Controller
{
    /**
     * Unretire a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('unretire', $wrestler);

        if (! $wrestler->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }

        $wrestler->unretire();

        return redirect()->route('wrestlers.index');
    }
}
