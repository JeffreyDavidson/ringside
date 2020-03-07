<?php

namespace App\Http\Requests\Referees;

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
        $this->user()->can('unretire', $this->route('referee'));

        if (! $$this->route('referee')->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }
    }
}
