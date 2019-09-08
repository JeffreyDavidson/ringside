<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;

class EmployController extends Controller
{
    /**
     * Employ a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('employ', $manager);

        $manager->employ();

        return redirect()->route('managers.index');
    }
}
