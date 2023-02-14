<?php

declare(strict_types=1);

namespace App\Http\Controllers\TagTeams;

use Illuminate\Http\RedirectResponse;
use App\Actions\TagTeams\ReinstateAction;
use App\Exceptions\CannotBeReinstatedException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class ReinstateController extends Controller
{
    /**
     * Reinstate a tag team.
     */
    public function __invoke(TagTeam $tagTeam): RedirectResponse
    {
        $this->authorize('reinstate', $tagTeam);

        try {
            ReinstateAction::run($tagTeam);
        } catch (CannotBeReinstatedException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('tag-teams.index');
    }
}
