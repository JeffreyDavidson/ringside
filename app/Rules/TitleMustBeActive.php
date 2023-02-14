<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Title;
use Illuminate\Contracts\Validation\Rule;

class TitleMustBeActive implements Rule
{
    /** @var string */
    protected $messages;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function passes(string $attribute, $value): bool
    {
        $title = Title::query()->whereKey($value)->sole();

        if (! $title->isCurrentlyActivated()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This title is not active and cannot be added to the match.';
    }
}
