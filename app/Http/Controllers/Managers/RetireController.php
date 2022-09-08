<?php

declare(strict_types=1);

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\RetireAction;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class RetireController extends Controller
{
    /**
     * Retire a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('retire', $manager);

        RetireAction::run($manager);

        return to_route('managers.index');
    }
}
