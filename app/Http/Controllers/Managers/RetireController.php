<?php

namespace App\Http\Controllers\Managers;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class RetireController extends Controller
{
    /**
     * Retire a manager.
     *
     * @param  App\Models\Manager  $manager
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('retire', $manager);

        if (! $manager->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $manager->retire();

        return redirect()->route('managers.index');
    }
}
