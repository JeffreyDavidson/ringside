<?php

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\SuspendAction;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class SuspendController extends Controller
{
    /**
     * Suspend a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \App\Actions\Managers\SuspendAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, SuspendAction $action)
    {
        $this->authorize('suspend', $manager);

        throw_unless($manager->canBeSuspended(), new CannotBeSuspendedException);

        $action->handle($manager);

        return redirect()->route('managers.index');
    }
}
