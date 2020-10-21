<?php

namespace App\Rules;

use App\Models\Stable;
use App\Models\Wrestler;
use Illuminate\Contracts\Validation\Rule;

class WrestlerCanJoinStable implements Rule
{
    /**
     * @var \App\Models\Stable
     */
    protected $stable;

    /**
     * Undocumented function.
     *
     * @param \App\Models\Stable $stable
     */
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
        $wrestler = Wrestler::find($value);

        if (! $wrestler) {
            return false;
        }

        if (($wrestler->isUnemployed() || $wrestler->hasFutureEmployment()) && $this->stable->isUnactivated()) {
            return true;
        }

        if ($wrestler->currentEmployment->started_at->isFuture()) {
            return false;
        }

        if ($wrestler->currentStable->doesntExist()) {
            return false;
        }

        if ($wrestler->currentStable->isNot($this->stable)) {
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
        return 'This wrestler cannot join this stable.';
    }
}
