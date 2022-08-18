<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Data\TagTeamData;
use App\Models\TagTeam;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Create a tag team.
     *
     * @param  \App\Data\TagTeamData  $tagTeamData
     * @return \App\Models\TagTeam
     */
    public function handle(TagTeamData $tagTeamData): TagTeam
    {
        /** @var \App\Models\TagTeam $tagTeam */
        $tagTeam = $this->tagTeamRepository->create($tagTeamData);

        if ($tagTeamData->wrestlerA) {
            AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerA, now());
        }

        if ($tagTeamData->wrestlerB) {
            AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerB, now());
        }

        if (isset($tagTeamData->start_date)) {
            EmployAction::run($tagTeam, $tagTeamData->start_date);
        }

        return $tagTeam;
    }
}
