<?php

declare(strict_types=1);

namespace App\Http\Controllers\TagTeams;

use Illuminate\Http\RedirectResponse;
use App\Actions\TagTeams\SuspendAction;
use App\Exceptions\CannotBeSuspendedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class SuspendController extends Controller
{
    /**
     * Suspend a tag team.
     */
    public function __invoke(TagTeam $tagTeam): RedirectResponse
    {
        $this->authorize('suspend', $tagTeam);

        try {
            SuspendAction::run($tagTeam);
        } catch (CannotBeSuspendedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('tag-teams.index');
    }
}
