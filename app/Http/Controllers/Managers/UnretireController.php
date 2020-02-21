<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class UnretireController extends Controller
{
    /**
     * Unretire a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('unretire', $manager);

        if (! $manager->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }

        $manager->unretire();

        return redirect()->route('managers.index');
    }
}
