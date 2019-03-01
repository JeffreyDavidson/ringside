<?php

namespace App\Rules;

use App\Models\TagTeam;
use Illuminate\Contracts\Validation\Rule;

class TagTeamCanJoinStable implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tagteam = TagTeam::find($value);

        if ($tagteam->hired_at->isFuture()) {
            return false;
        }

        if (!$tagteam->is_active) {
            return false;
        }

        if ($tagteam->stables()->exists()) {
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
