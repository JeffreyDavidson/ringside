<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

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

        if (! $manager->canBeEmployed()) {
            throw new CannotBeEmployedException();
        }

        $manager->employ();

        return redirect()->route('managers.index');
    }
}
