<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wrestlers;

use App\Actions\Wrestlers\UnretireAction;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class UnretireController extends Controller
{
    /**
     * Unretire a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler)
    {
        $this->authorize('unretire', $wrestler);

        UnretireAction::run($wrestler);

        return to_route('wrestlers.index');
    }
}
