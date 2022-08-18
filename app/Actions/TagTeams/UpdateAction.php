<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Data\TagTeamData;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Update a tag team.
     *
     * @param  \App\Models\TagTeam  $tagTeam
     * @param  \App\Data\TagTeamData  $tagTeamData
     * @return \App\Models\TagTeam
     */
    public function handle(TagTeam $tagTeam, TagTeamData $tagTeamData): TagTeam
    {
        $this->tagTeamRepository->update($tagTeam, $tagTeamData);

        $tagTeam->currentWrestlers
            ->diff(Wrestler::whereIn('id', [$tagTeamData->wrestlerA->id, $tagTeamData->wrestlerB->id])->get())
            ->each(fn ($wrestler) => RemoveTagTeamPartnerAction::run($tagTeam, $wrestler));

        if ($tagTeamData->wrestlerA && $tagTeam->currentWrestlers->doesntContain('id', $tagTeamData->wrestlerA->id)) {
            AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerA);
        }

        if ($tagTeamData->wrestlerB && $tagTeam->currentWrestlers->doesntContain('id', $tagTeamData->wrestlerB->id)) {
            AddTagTeamPartnerAction::run($tagTeam, $tagTeamData->wrestlerB);
        }

        if (isset($tagTeamData->start_date)) {
            if ($tagTeam->canBeEmployed() || $tagTeam->canHaveEmploymentStartDateChanged($tagTeamData->start_date)) {
                EmployAction::run($tagTeam, $tagTeamData->start_date);
            }
        }

        return $tagTeam;
    }
}
