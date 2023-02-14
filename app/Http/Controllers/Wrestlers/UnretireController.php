<?php

declare(strict_types=1);

namespace App\Http\Controllers\Wrestlers;

use Illuminate\Http\RedirectResponse;
use App\Actions\Wrestlers\UnretireAction;
use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Wrestler;

class UnretireController extends Controller
{
    /**
     * Unretire a wrestler.
     */
    public function __invoke(Wrestler $wrestler): RedirectResponse
    {
        $this->authorize('unretire', $wrestler);

        try {
            UnretireAction::run($wrestler);
        } catch (CannotBeUnretiredException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('wrestlers.index');
    }
}
