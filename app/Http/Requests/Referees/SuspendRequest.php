<?php

namespace App\Http\Requests\Referees;

use App\Exceptions\CannotBeSuspendedException;
use Illuminate\Foundation\Http\FormRequest;

class SuspendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('suspend', $this->route('referee'));

        if (! $this->route('referee')->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }
    }
}
