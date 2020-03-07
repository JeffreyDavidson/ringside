<?php

namespace App\Http\Requests\Referees;

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
        $this->user()->can('clearFromInjury', $this->route('referee'));

        if (! $$this->route('referee')->canBeClearedFromInjury()) {
            throw new CannotBeClearedFromInjuryException();
        }
    }
}
