<?php

namespace App\Http\Requests\Wrestlers;

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
        $this->user()->can('suspend', $this->route('wrestler'));

        if (! $$this->route('wrestler')->canBeSuspended()) {
            throw new CannotBeSuspendedException();
        }
    }
}
