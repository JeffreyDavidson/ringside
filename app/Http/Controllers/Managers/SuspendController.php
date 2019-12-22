<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Requests\Managers\SuspendRequest;

class SuspendController extends Controller
{
    /**
     * Suspend a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\SuspendRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, SuspendRequest $request)
    {
        if (!$request->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }

        $manager->suspend();

        return redirect()->route('managers.index');
    }
}
