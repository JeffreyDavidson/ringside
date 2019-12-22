<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Requests\Managers\InjureRequest;

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
        if (!$request->canBeInjured()) {
            throw new CannotBeInjuredException();
        }
        
        $manager->injure();

        return redirect()->route('managers.index');
    }
}
