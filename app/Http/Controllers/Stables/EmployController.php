<?php

namespace App\Http\Controllers\Stables;

use App\Models\Stable;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a stable.
     *
     * @param  App\Models\Stable  $stable
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable)
    {
        $this->authorize('employ', $stable);

        $stable->employ();

        return redirect()->route('stables.index');
    }
}
