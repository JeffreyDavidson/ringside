<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeMarkedAsHealedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Managers\RecoverFromInjuryRequest;
use App\Models\Manager;

class RecoverFromInjuryController extends Controller
{
    /**
     * Recover a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\RecoverFromInjuryRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, RecoverFromInjuryRequest $request)
    {
        if (! $manager->canBeMarkedAsHealed()) {
            throw new CannotBeMarkedAsHealedException();
        }

        $manager->heal();

        return redirect()->route('managers.index');
    }
}
