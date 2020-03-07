<?php

namespace App\Http\Requests\Wrestlers;

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
        $this->user()->can('injure', $this->route('wrestler'));

        if (! $$this->route('wrestler')->canBeInjured()) {
            throw new CannotBeInjuredException();
        }
    }
}
