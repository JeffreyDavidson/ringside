<?php

declare(strict_types=1);

namespace App\Http\Controllers\Managers;

use App\Actions\Managers\ClearInjuryAction;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class ClearInjuryController extends Controller
{
    /**
     * Clear a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager)
    {
        $this->authorize('clearFromInjury', $manager);

        ClearInjuryAction::run($manager);

        return to_route('managers.index');
    }
}
