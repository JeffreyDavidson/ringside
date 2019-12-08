<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Requests\Managers\InjureManagerRequest;

class InjureController extends Controller
{
    /**
     * Injure a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\InjureManagerRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, InjureManagerRequest $request)
    {
        if (!$request->canBeInjured()) {
            throw new CannotBeInjuredException();
        }
        
        $manager->injure();

        return redirect()->route('managers.index');
    }
}
