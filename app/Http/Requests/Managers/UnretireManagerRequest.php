<?php

namespace App\Http\Requests\Managers;

use Illuminate\Foundation\Http\FormRequest;

class UnretireManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('unretire', $this->route('manager'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Determine if the manager can be unretired.
     *
     * @return boolean
     */
    public function canBeUnretired()
    {
        if (!$this->route('manager')->isRetired()) {
            return false;
        }

        return true;
    }
}
