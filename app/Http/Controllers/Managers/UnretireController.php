<?php

declare(strict_types=1);

namespace App\Http\Controllers\Managers;

use Illuminate\Http\RedirectResponse;
use App\Actions\Managers\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Manager;

class UnretireController extends Controller
{
    /**
     * Unretire a manager.
     */
    public function __invoke(Manager $manager): RedirectResponse
    {
        $this->authorize('unretire', $manager);

        try {
            UnretireAction::run($manager);
        } catch (CannotBeUnretiredException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('managers.index');
    }
}
