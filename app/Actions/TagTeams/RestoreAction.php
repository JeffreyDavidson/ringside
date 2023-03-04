<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Models\TagTeam;
use Lorisleiva\Actions\Concerns\AsAction;

class RestoreAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Restore a tag team.
     */
    public function handle(TagTeam $tagTeam): void
    {
        dd($tagTeam->wrestlers);
        $tagTeam->previousWrestlers
            ->map(function ($wrestler) {
                if ($wrestler->isAMemberOfCurrentTagTeam) {
                    throw new \Exception('One or both of the previous wrestlers for this tag team are members of a current tag team so this tag team cannot be restored.');
                }

                return;
            });

        $this->tagTeamRepository->restore($tagTeam);
    }
}
