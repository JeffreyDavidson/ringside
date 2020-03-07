<?php

namespace App\Http\Requests\Wrestlers;

use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Http\FormRequest;

class ReinstateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('reinstate', $this->route('wrestler'));

        if (! $$this->route('wrestler')->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }
    }
}
