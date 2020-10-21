<?php

namespace App\Rules;

use App\Models\Stable;
use App\Models\TagTeam;
use Illuminate\Contracts\Validation\Rule;

class TagTeamCanJoinStable implements Rule
{
    /**
     * @var \App\Models\Stable
     */
    protected $stable;

    public function __construct(Stable $stable)
    {
        $this->stable = $stable;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tagTeam = TagTeam::find($value);

        if (! $tagTeam) {
            return false;
        }

        if (($tagTeam->isUnemployed() || $tagTeam->hasFutureEmployment()) && $this->stable->isUnactivated()) {
            return true;
        }

        if ($tagTeam->currentEmployment->started_at->isFuture()) {
            return false;
        }

        if ($tagTeam->currentStable->doesntExist()) {
            return false;
        }

        if ($tagTeam->currentStable->isNot($this->stable)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This tag team cannot join this stable.';
    }
}
