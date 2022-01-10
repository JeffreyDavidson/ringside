<?php

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\EmployAction;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class EmployController extends Controller
{
    /**
     * Employ a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \App\Actions\Managers\EmployAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, EmployAction $action)
    {
        $this->authorize('employ', $manager);

        throw_unless($manager->canBeEmployed(), new CannotBeEmployedException);

        $action->handle($manager);

        return redirect()->route('managers.index');
    }
}
