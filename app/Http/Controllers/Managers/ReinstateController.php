<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Requests\Managers\ReinstateRequest;

class ReinstateController extends Controller
{
    /**
     * Reinstate a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\ReinstateRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, ReinstateRequest $request)
    {
        if (!$request->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }

        $manager->reinstate();

        return redirect()->route('managers.index');
    }
}
