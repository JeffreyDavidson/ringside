<?php

namespace App\Http\Controllers\Wrestlers;

use App\Models\Wrestler;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a wrestler.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('employ', $wrestler);

        $wrestler->employ();

        return redirect()->route('wrestlers.index');
    }
}
