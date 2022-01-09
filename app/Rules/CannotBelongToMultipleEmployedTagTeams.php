<?php

namespace App\Rules;

use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Contracts\Validation\Rule;

class CannotBelongToMultipleEmployedTagTeams implements Rule
{
    /**
     * @var \App\Models\Wrestler
     */
    private Wrestler $wrestler;

    /**
     * @var TagTeam|null
     */
    private $tagTeam;

    public function __construct(TagTeam $tagTeam = null)
    {
        $this->tagTeam = $tagTeam;
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
        if ($this->wrestler->currentTagTeam->is($this->tagTeam)) {
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
        return $this->wrestler->name.' is already a part of a current tag team.';
    }
}
