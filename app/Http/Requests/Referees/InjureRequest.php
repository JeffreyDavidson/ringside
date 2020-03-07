<?php

namespace App\Http\Requests\Referees;

use App\Exceptions\CannotBeInjuredException;
use Illuminate\Foundation\Http\FormRequest;

class InjureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('injure', $this->route('referee'));

        if (! $$this->route('referee')->canBeInjured()) {
            throw new CannotBeInjuredException();
        }
    }
}
