<?php

use Carbon\Carbon;
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

    public function create($attributes = [])
    {
        $retirement = new Retirement();
        $retirement->started_at = $this->startDate ?? now();

        if ($this->endDate) {
            $retirement->ended_at = $this->endDate;
        }

        if ($this->wrestlers && ! empty($this->wrestlers)) {
            if (count($this->wrestlers) === 1) {
                $retirement->retiree()->associate($this->wrestler);
                $retirement->save();

                return $retirement;
            }
            $wrestlers = new Collection();
            foreach ($this->wrestlers as $wrestler) {
                $clone = clone $this;
                $clone->startDate = $retirement->started_at;
                $clone->endDate = $retirement->ended_at;
                $wrestlers->push($clone->forWrestler($wrestler)->create());
            }

            return $wrestlers;
        }

        if ($this->tagTeam) {
            $retirement->retiree()->associate($this->tagTeam);
            $retirement->save();

            if ($this->tagTeam->wrestlers->isNotEmpty()) {
                $clone = clone $this;
                $clone->tagTeam = null;
                $clone->startDate = $retirement->started_at;
                $clone->endDate = $retirement->ended_at;
                $clone->forWrestlers($this->tagTeam->wrestlers);
                $clone->create();
            }

            return $retirement;
        }

        throw new \Exception('Attempted to create an retirement without an employable entity');
    }
}
