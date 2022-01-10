<?php

namespace App\Http\Controllers\Stables;

use App\Actions\Stables\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Stable;

class UnretireController extends Controller
{
    /**
     * Unretire a stable.
     *
     * @param  \App\Models\Stable  $stable
     * @param  \App\Actions\Stables\UnretireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Stable $stable, UnretireAction $action)
    {
        $this->authorize('unretire', $stable);

        throw_unless($stable->canBeUnretired(), new CannotBeUnretiredException);

        $action->handle($stable);

        return redirect()->route('stables.index');
    }
}
