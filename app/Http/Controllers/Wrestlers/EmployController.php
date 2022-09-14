<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\EmployAction;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class EmployController extends Controller
{
    /**
     * Employ a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('employ', $wrestler);

        try {
            EmployAction::run($wrestler);
        } catch (CannotBeEmployedException $e) {
            return back()->with('error', 'This wrestler cannot be employed.');
        }

        return to_route('wrestlers.index');
    }
}
