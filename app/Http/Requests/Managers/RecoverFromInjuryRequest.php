<?php

namespace App\Http\Requests\Managers;

use Illuminate\Foundation\Http\FormRequest;

class RecoverFromInjuryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('markAsRecovered', $this->route('manager'));
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
     * Determine if the manager can be marked as healed.
     *
     * @return boolean
     */
    public function canBeMarkedAsHealed()
    {
        if (!$this->route('manager')->isInjured()) {
            return false;
        }

        return true;
    }
}
