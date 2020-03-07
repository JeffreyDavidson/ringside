<?php

namespace App\Http\Requests\Referees;

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
        $this->user()->can('reinstate', $this->route('referee'));

        if (! $$this->route('referee')->canBeReinstated()) {
            throw new CannotBeReinstatedException();
        }
    }
}
