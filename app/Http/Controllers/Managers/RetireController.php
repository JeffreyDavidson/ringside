<?php

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\RetireAction;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class RetireController extends Controller
{
    /**
     * Retire a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \App\Actions\Managers\RetireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, RetireAction $action)
    {
        $this->authorize('retire', $manager);

        throw_unless($manager->canBeRetired(), new CannotBeRetiredException);

        $action->handle($manager);

        return redirect()->route('managers.index');
    }
}
