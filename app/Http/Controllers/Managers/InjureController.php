<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class InjureController extends Controller
{
    /**
     * Injure a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('injure', $manager);

        if (! $manager->canBeInjured()) {
            throw new CannotBeInjuredException();
        }

        $manager->injure();

        return redirect()->route('managers.index');
    }
}
