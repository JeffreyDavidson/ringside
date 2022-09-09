<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\ReinstateAction;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ReinstateController extends Controller
{
    /**
     * Reinstate a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('reinstate', $wrestler);

        ReinstateAction::run($wrestler);

        return to_route('wrestlers.index');
    }
}
