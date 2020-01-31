<?php

use Carbon\Carbon;
use App\Models\Manager;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Models\Employment;
use Illuminate\Support\Collection;

class EmploymentFactory extends BaseFactory
{
    /** @var \Carbon\Carbon|null */
    public $startDate;
    /** @var \Carbon\Carbon|null */
    public $endDate;
    /** @var TagTeam */
    public $tagTeam;
    /** @var Wrestler[] */
    public $wrestlers;

    public function __construct()
    {
    }

    public static function new()
    {
        return new static();
    }

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
        $clone = clone $this;
        $clone->tagTeam = $tagTeam;

        return $clone;
    }

    public function forWrestler(Wrestler $wrestler)
    {
        $clone = clone $this;
        $clone->wrestlers = [$wrestler];

        return $clone;
    }

    public function forWrestlers($wrestlers)
    {
        $clone = clone $this;
        $clone->wrestlers = $wrestlers;

        return $clone;
    }

    public function forManager(Manager $manager)
    {
        $clone = clone $this;
        $clone->managers = [$manager];

        return $clone;
    }

    public function create($attributes = [])
    {
        $employment = new Employment();
        $employment->started_at = $this->startDate ?? now();

        if ($this->endDate) {
            $employment->ended_at = $this->endDate;
        }

        if ($this->wrestlers && ! empty($this->wrestlers)) {
            if (count($this->wrestlers) === 1) {
                $employment->employable()->associate($this->wrestlers[0]);
                $employment->save();

                return $employment;
            }

            $wrestlers = new Collection();

            foreach ($this->wrestlers as $wrestler) {
                $clone = clone $this;
                $clone->startDate = $employment->started_at;
                $clone->endDate = $employment->ended_at;
                $wrestlers->push($clone->forWrestler($wrestler)->create());
            }

            return $wrestlers;
        }

        if ($this->tagTeam) {
            $employment->employable()->associate($this->tagTeam);
            $employment->save();


            if ($this->tagTeam->currentWrestlers->isNotEmpty()) {
                $clone = clone $this;
                $clone->tagTeam = null;
                $clone->startDate = $employment->started_at;
                $clone->endDate = $employment->ended_at;
                $clone = $clone->forWrestlers($this->tagTeam->currentWrestlers);
                $clone->create();
            }

            return $employment;
        }

        throw new \Exception('Attempted to create an Employment without an employable entity');
    }
}
