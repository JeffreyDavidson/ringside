<?php

namespace App\Rules;

use App\Models\Stable;
use App\Models\TagTeam;
use Illuminate\Contracts\Validation\Rule;

class TagTeamCanJoinStable implements Rule
{
    /**
     * The message to be sent as the validation message.
     *
     * @var string
     */
    protected string $message;

    /**
     * The stable to be checked against.
     *
     * @var \App\Models\Stable
     */
    protected Stable $stable;

    /**
     * The start date of the stable.
     *
     * @var string|null
     */
    protected ?string $startedAt;

    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\Stable $stable
     * @param  string|null $startedAt
     *
     * @return void
     */
    public function __construct(Stable $stable, string $startedAt = null)
    {
        $this->stable = $stable;
        $this->startedAt = $startedAt;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tagTeam = TagTeam::with(['currentStable', 'futureEmployment'])->whereKey($value)->sole();

        if ($tagTeam->currentStable && $tagTeam->currentStable->isNot($this->stable)) {
            $this->setMessage('This tag team is already a members of an active stable.');

            return false;
        }

        if (is_string($this->startedAt)) {
            if ($tagTeam->futureEmployment && $tagTeam->futureEmployment->startedAfter($this->startedAt)) {
                $this->setMessage("This tag team's future employment starts after stable's start date.");

                return false;
            }
        }

        return true;
    }

    /**
     * Set the message of the validation error.
     *
     * @param  string $message
     *
     * @return void
     */
    protected function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
