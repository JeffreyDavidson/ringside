<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Managers\RetireRequest;
use App\Models\Manager;

class RetireController extends Controller
{
    /**
     * Retire a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\RetireRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, RetireRequest $request)
    {
        if (! $manager->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $manager->retire();

        return redirect()->route('managers.index');
    }
}
