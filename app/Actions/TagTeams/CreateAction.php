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

        $this->tagTeamRepository->addTagTeamPartner($tagTeam, $tagTeamData->wrestlerA, now());
        $this->tagTeamRepository->addTagTeamPartner($tagTeam, $tagTeamData->wrestlerB, now());

        if (isset($tagTeamData->start_date)) {
            $this->tagTeamRepository->employ($tagTeam, $tagTeamData->start_date);

            event(new TagTeamEmployed($tagTeam, $tagTeamData->start_date));
        }

        return $tagTeam;
    }
}
