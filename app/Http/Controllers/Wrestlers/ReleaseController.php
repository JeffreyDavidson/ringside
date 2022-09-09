<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\ReleaseAction;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class ReleaseController extends Controller
{
    /**
     * Release a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('release', $wrestler);

        ReleaseAction::run($wrestler);

        return to_route('wrestlers.index');
    }
}
