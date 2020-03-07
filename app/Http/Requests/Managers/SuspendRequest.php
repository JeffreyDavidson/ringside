<?php

namespace App\Http\Requests\Managers;

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
        $this->user()->can('suspend', $this->route('manager'));

        if (! $$this->route('manager')->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }
    }
}
