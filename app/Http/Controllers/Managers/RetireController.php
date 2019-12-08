<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Requests\Managers\RetireManagerRequest;

class RetireController extends Controller
{
    /**
     * Retire a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\RetireManagerRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, RetireManagerRequest $request)
    {
        if (!$request->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $manager->retire();

        return redirect()->route('managers.index');
    }
}
