<?php

namespace App\Http\Requests\Referees;

use App\Exceptions\CannotBeEmployedException;
use Illuminate\Foundation\Http\FormRequest;

class EmployRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->user()->can('employ', $this->route('referee'));

        if (! $this->route('referee')->canBeEmployed()) {
            throw new CannotBeEmployedException();
        }
    }
}
