<?php

namespace App\Rules;

use App\Models\Wrestler;
use Illuminate\Contracts\Validation\Rule;

class CanJoinTagTeam implements Rule
{
    protected $startedAt;

    public function __construct(string $startedAt)
    {
        $this->startedAt = $startedAt;
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

        if ($wrestler->isEmployed()) {
            if ($wrestler->isSuspended() || $wrestler->isRetired() || $wrestler->isInjured()) {
                return false;
            }

            if ($wrestler->currentEmployment->started_at->gt($this->started_at)) {
                return false;
            }

            if ($wrestler->currentTagTeam()->exists()) {
                return false;
            }
        } else {
            if ($wrestler->has('pendingEmployment')) {
                if ($wrestler->pendingEmployment->started_at->gt($this->started_at)) {
                    return false;
                }
            }
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
        return 'A wrestler is not allowed to be added to this tag team.';
    }
}
