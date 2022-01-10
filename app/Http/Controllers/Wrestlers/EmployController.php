<?php

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
     * @param  \App\Actions\Wrestlers\EmployAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler, EmployAction $action)
    {
        $this->authorize('employ', $wrestler);

        throw_unless($wrestler->canBeEmployed(), new CannotBeEmployedException);

        $action->handle($wrestler);

        return redirect()->route('wrestlers.index');
    }
}
