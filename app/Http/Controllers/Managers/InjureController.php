<?php

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\InjureAction;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class InjureController extends Controller
{
    /**
     * Injure a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \App\Actions\Managers\InjureAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, InjureAction $action)
    {
        $this->authorize('injure', $manager);

        throw_unless($manager->canBeInjured(), new CannotBeInjuredException);

        $action->handle($manager);

        return redirect()->route('managers.index');
    }
}
