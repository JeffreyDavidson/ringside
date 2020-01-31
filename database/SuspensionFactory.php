<?php

use App\Models\Suspension;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SuspensionFactory extends BaseFactory
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
        $suspension = new Suspension();
        $suspension->started_at = $this->startDate ?? now();
        if ($this->endDate) {
            $suspension->ended_at = $this->endDate;
        }
        if ($this->wrestlers && ! empty($this->wrestlers)) {
            if (count($this->wrestlers) === 1) {
                $suspension->suspendable()->associate($this->wrestler);
                $suspension->save();

                return $suspension;
            }

            $wrestlers = new Collection();

            foreach ($this->wrestlers as $wrestler) {
                $clone = clone $this;
                $clone->startDate = $suspension->started_at;
                $clone->endDate = $suspension->ended_at;
                $wrestlers->push($clone->forWrestler($wrestler)->create());
            }

            return $wrestlers;
        }

        if ($this->tagTeam) {
            $suspension->suspendable()->associate($this->tagTeam);
            $suspension->save();

            if ($this->tagTeam->wrestlers->isNotEmpty()) {
                $clone = clone $this;
                $clone->tagTeam = null;
                $clone->startDate = $suspension->started_at;
                $clone->endDate = $suspension->ended_at;
                $clone->forWrestlers($this->tagTeam->wrestlers);
                $clone->create();
            }

            return $suspension;
        }

        throw new \Exception('Attempted to create an suspension without an employable entity');
    }
}
