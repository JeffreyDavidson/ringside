<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class ReinstateController extends Controller
{
    /**
     * Reinstate a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('reinstate', $manager);

        if (! $manager->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }

        $manager->reinstate();

        return redirect()->route('managers.index');
    }
}
