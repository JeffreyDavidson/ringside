<?php

namespace App\Http\Controllers\Referees;

use App\Models\Referee;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a pending introduced referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee)
    {
        $this->authorize('employ', $referee);

        $referee->employ();

        return redirect()->route('referees.index');
    }
}
