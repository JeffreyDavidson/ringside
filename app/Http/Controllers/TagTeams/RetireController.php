<?php

declare(strict_types=1);

namespace App\Http\Controllers\TagTeams;

use Illuminate\Http\RedirectResponse;
use App\Actions\TagTeams\RetireAction;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\TagTeam;

class RetireController extends Controller
{
    /**
     * Retire a tag team.
     */
    public function __invoke(TagTeam $tagTeam): RedirectResponse
    {
        $this->authorize('retire', $tagTeam);

        try {
            RetireAction::run($tagTeam);
        } catch (CannotBeRetiredException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return to_route('tag-teams.index');
    }
}
