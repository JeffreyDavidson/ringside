<?php

declare(strict_types=1);

namespace App\Http\Controllers\Managers;

use Illuminate\Http\RedirectResponse;
use App\Actions\Managers\InjureAction;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class InjureController extends Controller
{
    /**
     * Injure a manager.
     */
    public function __invoke(Manager $manager): RedirectResponse
    {
        $this->authorize('injure', $manager);

        try {
            InjureAction::run($manager);
        } catch (CannotBeInjuredException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('managers.index');
    }
}
