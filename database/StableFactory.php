<?php

use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;

class StableFactory extends BaseFactory
{
    public $wrestlersCount = 0;
    public $tagTeamsCount = 0;
    public $startedAtDate = null;
    public $states = null;

    /**
     * Give the number of wrestler members for the stable.
     *
     * @param  int $count
     * @return void
     */
    public function withWrestlers($count)
    {
        $this->withWrestlersCount = $count;

        return $this;
    }

    /**
     * Give the number of tag team members for the stable.
     *
     * @param  int $count
     * @return void
     */
    public function withTagTeams($count)
    {
        $this->withTagTeamssCount = $count;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function create()
    {
        $stable = factory(Stable::class)->states($this->states)->create();
        $wrestlers = factory(Wrestler::class, $this->wrestlersCount)->states('bookable')->create();
        $tagTeams = factory(TagTeam::class, $this->tagTeamsCount)->states('bookable')->create();

        $stable->wrestlerHistory()->attach($wrestlers, ['joined_at' => $this->startedAtDate]);
        $stable->tagTeamHistory()->attach($tagTeams, ['joined_at' => $this->startedAtDate]);

        return $stable;
    }
}
