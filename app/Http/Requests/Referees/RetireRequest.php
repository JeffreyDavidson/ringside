<?php

namespace App\Http\Requests\Referees;

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
        $this->user()->can('retire', $this->route('referee'));

        if (! $this->route('referee')->canBeRetired()) {
            throw new CannotBeRetiredException();
        }
    }
}
