<?php

use Carbon\Carbon;
use App\Models\Manager;
use App\Models\Referee;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Models\Retirement;
use Illuminate\Support\Collection;

class RetirementFactory extends BaseFactory
{
    /** @var \Carbon\Carbon|null */
    public $startDate;
    /** @var \Carbon\Carbon|null */
    public $endDate;
    /** @var TagTeam[] */
    public $tagTeams;
    /** @var Wrestler[] */
    public $wrestlers;
    /** @var Manager[] */
    public $managers;
    /** @var Referee[] */
    public $referees;

    /**
     * @param string|Carbon $startDate
     */
    public function started($startDate = 'now')
    {
        $clone = clone $this;
        $clone->startDate = $startDate instanceof Carbon ? $startDate : new Carbon($startDate);

        return $clone;
    }

    /**
     * @param string|Carbon $endDate
     */
    public function ended($endDate = 'now')
    {
        $clone = clone $this;
        $clone->endDate = $endDate instanceof Carbon ? $endDate : new Carbon($endDate);

        return $clone;
    }

    public function forTagTeam(TagTeam $tagTeam)
    {
        return $this->forTagTeams([$tagTeam]);
    }

    public function forTagTeams($tagTeams)
    {
        $clone = clone $this;
        $clone->tagTeams = $tagTeams;

        return $clone;
    }

    public function forWrestler(Wrestler $wrestler)
    {
        return $this->forWrestlers([$wrestler]);
    }

    public function forWrestlers($wrestlers)
    {
        $clone = clone $this;
        $clone->wrestlers = $wrestlers;

        return $clone;
    }

    public function forManager(Manager $manager)
    {
        return $this->forManagers([$manager]);
    }

    public function forManagers($managers)
    {
        $clone = clone $this;
        $clone->managers = $managers;

        return $clone;
    }

    public function forReferee(Referee $referee)
    {
        return $this->forReferees([$referee]);
    }

    public function forReferees($referees)
    {
        $clone = clone $this;
        $clone->referees = $referees;

        return $clone;
    }

    public function create($attributes = [])
    {
        $retirees = collect()
            ->merge($this->tagTeams)
            ->merge($this->wrestlers)
            ->merge($this->referees)
            ->merge($this->managers)
            ->flatten(1);


        $this->startDate = $this->startDate ?? now();

        if (empty($retirees)) {
            throw new \Exception('Attempted to create an retirement without a retireable entity');
        }

        $retirements = new Collection();

        foreach ($retirees as $retiree) {
            $retirement = new Retirement();
            $retirement->started_at = $this->startDate;
            $retirement->ended_at = $this->endDate;
            $retirement->retiree()->associate($retiree);
            $retirement->save();
            $retirements->push($retirement);
            if ($retiree instanceof TagTeam && $retiree->currentWrestlers->isNotEmpty()) {
                $this->forWrestlers($retiree->currentWrestlers)->create();
            }
        }

        return $retirements->count() === 1 ? $retirements->first() : $retirements;
    }
}
