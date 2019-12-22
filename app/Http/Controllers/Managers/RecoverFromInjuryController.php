<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeMarkedAsHealedException;
use App\Http\Requests\Managers\RecoverFromInjuryRequest;

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
        if (!$request->canBeMarkedAsHealed()) {
            throw new CannotBeMarkedAsHealedException();
        }

        $manager->heal();

        return redirect()->route('managers.index');
    }
}
