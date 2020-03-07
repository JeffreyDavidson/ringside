<?php

namespace App\Http\Requests\Wrestlers;

use App\Exceptions\CannotBeRetiredException;
use Illuminate\Foundation\Http\FormRequest;

class RetireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('retire', $this->route('wrestler'));

        if (! $$this->route('wrestler')->canBeRetired()) {
            throw new CannotBeRetiredException();
        }
    }
}
