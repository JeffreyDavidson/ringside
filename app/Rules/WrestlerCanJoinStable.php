<?php

namespace App\Rules;

use App\Models\Stable;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class WrestlerCanJoinStable implements Rule
{
    /**
     * The message to be sent as the validation message.
     *
     * @var string
     */
    protected string $message;

    /**
     * The stable to check against.
     *
     * @var \App\Models\Stable
     */
    protected Stable $stable;

    /**
     * The start date of the stable.
     *
     * @var \Carbon\Carbon|null
     */
    protected $startedAt;

    /**
     * Create a new rule instance.
     *
     * @param  \App\Models\Stable $stable
     * @param  \Carbon\Carbon|null $startedAt
     *
     * @return void
     */
    public function __construct(Stable $stable, Carbon $startedAt = null)
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
        $wrestler = Wrestler::with(['currentStable', 'futureEmployment'])->whereKey($value)->sole();

        if (! $wrestler->isNotCurrentlyInStable($this->stable)) {
            $this->setMessage('This wrestler is already a member of an active stable.');

            return false;
        }

        if ($wrestler->employedBefore($this->startedAt)) {
            $this->setMessage("This wrestler's future employment starts after stable's start date.");

            return false;
        }

        return true;
    }

    /**
     * Set the message of the error message.
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
