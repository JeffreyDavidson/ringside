<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Managers\InjureRequest;
use App\Models\Manager;

class InjureController extends Controller
{
    /**
     * Injure a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\InjureRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, InjureRequest $request)
    {
        if (! $manager->canBeInjured()) {
            throw new CannotBeInjuredException();
        }

        $manager->injure();

        return redirect()->route('managers.index');
    }
}
