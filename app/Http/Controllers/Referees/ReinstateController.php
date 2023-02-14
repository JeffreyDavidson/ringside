<?php

declare(strict_types=1);

namespace App\Http\Controllers\Referees;

use Illuminate\Http\RedirectResponse;
use App\Actions\Referees\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\Referee;

class ReinstateController extends Controller
{
    /**
     * Reinstate a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Referee $referee): RedirectResponse
    {
        $this->authorize('reinstate', $referee);

        try {
            ReinstateAction::run($referee);
        } catch (CannotBeReinstatedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('referees.index');
    }
}
