<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Exceptions\CannotJoinTagTeamException;
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
        $this->ensureCanBeRestored($tagTeam);

        $this->tagTeamRepository->restore($tagTeam);
    }

    public function ensureCanBeRestored(TagTeam $tagTeam): void
    {
        $tagTeam->previousWrestlers
            ->each(function ($wrestler) {
                if ($wrestler->isAMemberOfCurrentTagTeam()) {
                    throw CannotJoinTagTeamException::alreadyInTagTeam();
                }
            });
    }
}
