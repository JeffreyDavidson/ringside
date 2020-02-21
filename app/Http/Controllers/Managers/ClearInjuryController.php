<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeClearedFromInjuryException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class ClearInjuryController extends Controller
{
    /**
     * Clear a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('clearFromInjury', $manager);

        if (! $manager->canBeClearedFromInjury()) {
            throw new CannotBeClearedFromInjuryException();
        }

        $manager->clearFromInjury();

        return redirect()->route('managers.index');
    }
}
