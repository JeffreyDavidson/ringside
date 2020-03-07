<?php

namespace App\Http\Requests\Managers;

use App\Exceptions\CannotBeClearedFromInjuryException;
use Illuminate\Foundation\Http\FormRequest;

class ClearInjuryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('clearFromInjury', $this->route('manager'));

        if (! $$this->route('manager')->canBeClearedFromInjury()) {
            throw new CannotBeClearedFromInjuryException();
        }
    }
}
