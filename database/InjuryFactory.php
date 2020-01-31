<?php

use Carbon\Carbon;
use App\Models\Injury;
use App\Models\Wrestler;
use Illuminate\Support\Collection;

class InjuryFactory extends BaseFactory
{
    /** @var \Carbon\Carbon|null */
    public $startDate;
    /** @var \Carbon\Carbon|null */
    public $endDate;
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
        $injury = new Injury();
        $injury->started_at = $this->startDate ?? now();

        if ($this->endDate) {
            $injury->ended_at = $this->endDate;
        }

        if ($this->wrestlers && ! empty($this->wrestlers)) {
            if (count($this->wrestlers) === 1) {
                $injury->injurable()->associate($this->wrestler);
                $injury->save();

                return $injury;
            }

            $wrestlers = new Collection();

            foreach ($this->wrestlers as $wrestler) {
                $clone = clone $this;
                $clone->startDate = $injury->started_at;
                $clone->endDate = $injury->ended_at;
                $wrestlers->push($clone->forWrestler($wrestler)->create());
            }

            return $wrestlers;
        }

        throw new \Exception('Attempted to create an injury without an employable entity');
    }
}
