<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StableHasEnoughMembers implements Rule
{
    private ?array $tagTeamIds = [];
    private ?array $wrestlerIds = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?array $tagTeamIds, ?array $wrestlerIds)
    {
        $this->tagTeamIds = $tagTeamIds;
        $this->wrestlerIds = $wrestlerIds;
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
        $tagTeamsCount = count($this->tagTeamIds);
        $wrestlersCount = count($this->wrestlerIds);

        if ($tagTeamsCount >= 2) {
            return true;
        }

        if ($wrestlersCount >= 3) {
            return true;
        }

        if ($tagTeamsCount == 1 && $wrestlersCount >= 1) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'There is not enough members for this stable.';
    }
}
