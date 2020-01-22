<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class RetireController extends Controller
{
    /**
     * Retire a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('retire', $wrestler);

        if (! $wrestler->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $wrestler->retire();

        return redirect()->route('wrestlers.index');
    }
}
