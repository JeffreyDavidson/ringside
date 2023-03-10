<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Data\TagTeamData;
use App\Events\TagTeams\TagTeamEmployed;
use App\Models\TagTeam;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Create a tag team.
     */
    public function handle(TagTeamData $tagTeamData): TagTeam
    {
        /** @var \App\Models\TagTeam $tagTeam */
        $tagTeam = $this->tagTeamRepository->create($tagTeamData);

        if (! isset($tagTeamData->wrestlerA, $tagTeamData->wrestlerB)) {
            return $tagTeam;
        }

        AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerA);
        AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerB);

        if (isset($tagTeamData->start_date)) {
            EmployAction::run($tagTeam, $tagTeamData->start_date);

            event(new TagTeamEmployed($tagTeam, $tagTeamData->start_date));
        }

        return $tagTeam;
    }
}
