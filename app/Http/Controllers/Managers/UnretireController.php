<?php

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class UnretireController extends Controller
{
    /**
     * Unretire a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \App\Actions\Managers\UnretireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, UnretireAction $action)
    {
        $this->authorize('unretire', $manager);

        throw_unless($manager->canBeUnretired(), new CannotBeUnretiredException);

        $action->handle($manager);

        return redirect()->route('managers.index');
    }
}
