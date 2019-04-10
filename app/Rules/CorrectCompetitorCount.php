<?php

namespace App\Rules;

use App\Models\MatchType;
use Illuminate\Contracts\Validation\Rule;

class CorrectCompetitorCount implements Rule
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
        return $this->size($value) == $this->expected($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Incorrect number of competitors.';
    }

    /**
     * Get the amount of competitors added for the match
     *
     * @param  integer  $value
     * @return integer
     */
    private function size($value): int
    {
        return count($value['competitors']);
    }

    /**
     * Get the amount of competitors for the given match type.
     *
     * @param  integer  $value
     * @return integer
     */
    private function expected($value): int
    {
        $type = MatchType::find($value['match_type_id']);

        return $type->number_of_sides;
    }
}
