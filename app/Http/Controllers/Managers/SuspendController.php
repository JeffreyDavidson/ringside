<?php

declare(strict_types=1);

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\SuspendAction;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class SuspendController extends Controller
{
    /**
     * Suspend a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('suspend', $manager);

        SuspendAction::run($manager);

        return to_route('managers.index');
    }
}
