<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Managers\SuspendRequest;
use App\Models\Manager;

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
        if (! $manager->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }

        $manager->suspend();

        return redirect()->route('managers.index');
    }
}
