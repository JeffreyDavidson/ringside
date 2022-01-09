<?php

namespace App\Rules;

use App\Models\Stable;
use App\Models\Wrestler;
use Illuminate\Contracts\Validation\Rule;

class WrestlerCanJoinStable implements Rule
{
    /**
     * @var string
     */
    protected string $message;

    /**
     * @var \App\Models\Stable
     */
    protected Stable $stable;

    /**
     * @var string|null
     */
    protected ?string $startedAt;

    /**
     * Create a new rule instance.
     *
     * @param \App\Models\Stable $stable
     * @param string|null $startedAt
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
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $wrestler = Wrestler::with(['currentStable', 'futureEmployment'])->sole($value);

        if ($wrestler->currentStable->isNot($this->stable)) {
            $this->setMessage('This wrestler is already a member of an active stable.');

            return false;
        }

        if (is_string($this->startedAt)) {
            if ($wrestler->futureEmployment && $wrestler->futureEmployment->startedAfter($this->startedAt)) {
                $this->setMessage("This wrestler's future employment starts after stable's start date.");

                return false;
            }
        }

        return true;
    }

    /**
     * Set the message of the error message.
     *
     * @param  string $message
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
