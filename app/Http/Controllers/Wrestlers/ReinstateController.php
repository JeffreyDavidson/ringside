<?php

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ReinstateController extends Controller
{
    /**
     * Reinstate a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \App\Actions\Wrestlers\ReinstateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler, ReinstateAction $action)
    {
        $this->authorize('reinstate', $wrestler);

        throw_unless($wrestler->canBeReinstated(), new CannotBeReinstatedException);

        $action->handle($wrestler);

        return redirect()->route('wrestlers.index');
    }
}
