<?php

namespace App\Rules;

use App\Models\TagTeam;

class WrestlerJoinedStableInTagTeam
{
    /**
     * Array of tag team ids to check against.
     *
     * @var int[]|null
     */
    private ?array $tagTeamIds = [];

    /**
     * Array of wrestler ids to check against.
     *
     * @var int[]|null
     */
    private ?array $wrestlerIds = [];

    /**
     * Create a new rule instance.
     *
     * @param  int[]|null $tagTeamIds
     * @param  int[]|null $wrestlerIds
     *
     * @return void
     */
    public function __construct(?array $tagTeamIds, ?array $wrestlerIds)
    {
        $this->tagTeamIds = $tagTeamIds;
        $this->wrestlerIds = $wrestlerIds;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @return bool
     */
    public function passes()
    {
        $wrestlerIdsCollection = collect($this->wrestlerIds);

        if ($wrestlerIdsCollection->isEmpty()) {
            return true;
        }

        $wrestlerIdsAddedFromTagTeams = collect();

        if (null === $this->tagTeamIds || count($this->tagTeamIds) == 0) {
            return false;
        }

        foreach ($this->tagTeamIds as $tagTeamId) {
            $tagTeam = TagTeam::with('currentWrestlers')->whereKey($tagTeamId)->sole();

            $tagTeamWrestlerIds = $tagTeam->currentWrestlers->pluck('id');

            foreach ($tagTeamWrestlerIds as $tagTeamWrestlerId) {
                $wrestlerIdsAddedFromTagTeams->push($tagTeamWrestlerId);
            }
        }

        $foundWrestlers = $wrestlerIdsAddedFromTagTeams->intersect($wrestlerIdsCollection);

        if ($foundWrestlers->isEmpty()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There are wrestlers that are added to the stable that were added from a tag team.';
    }
}
