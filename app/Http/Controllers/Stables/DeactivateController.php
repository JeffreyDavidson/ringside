<?php

namespace App\Http\Controllers\Stables;

use App\Actions\Stables\DeactivateAction;
use App\Exceptions\CannotBeDeactivatedException;
use App\Http\Controllers\Controller;
use App\Models\Stable;

class DeactivateController extends Controller
{
    /**
     * Deactivate a stable.
     *
     * @param  \App\Models\Stable $stable
     * @param  \App\Actions\Stables\DeactivateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable, DeactivateAction $action)
    {
        $this->authorize('deactivate', $stable);

        throw_unless($stable->canBeDeactivated(), new CannotBeDeactivatedException);

        $action->handle($stable);

        return redirect()->route('stables.index');
    }
}
