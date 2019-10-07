<?php

class TagTeamFactory
{
    public $wrestlersCount = 0;
    public $introducedAtDate = null;
    public $states = null;

    /**
     * Give the number of wrestler members for the tag team.
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
     * Undocumented function
     *
     * @return void
     */
    public function create()
    {
        $tagTeam = factory(TagTeam::class)->states($this->states)->create();
        $wrestlers = factory(Wrestler::class, $this->wrestlersCount)->states('bookable')->create();

        $tagTeam->wrestlerHistory()->attach($wrestlers, ['joined_at' => $this->introducedAtDate]);

        return $tagTeam;
    }
}
