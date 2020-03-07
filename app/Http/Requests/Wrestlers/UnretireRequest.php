<?php

namespace App\Http\Requests\Wrestlers;

use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Http\FormRequest;

class UnretireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('unretire', $this->route('wrestler'));

        if (! $$this->route('wrestler')->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }
    }
}
