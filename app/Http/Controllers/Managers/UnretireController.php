<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Requests\Managers\UnretireRequest;

class UnretireController extends Controller
{
    /**
     * Unretire a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\UnretireRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, UnretireRequest $request)
    {
        if (!$request->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }

        $manager->unretire();

        return redirect()->route('managers.index');
    }
}
