<?php

namespace App\Http\Requests\Managers;

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
        $this->user()->can('unretire', $this->route('manager'));

        if (! $$this->route('manager')->canBeUnretired()) {
            throw new CannotBeUnretiredException();
        }
    }
}
