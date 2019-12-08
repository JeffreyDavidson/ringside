<?php

namespace App\Http\Requests\Managers;

use App\Rules\WithoutActiveEmployment;
use Illuminate\Foundation\Http\FormRequest;

class EmployManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('employ', $this->route('manager'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'started_at' => [
                'nullable',
                new WithoutActiveEmployment($this->route('manager'))
            ]
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['started_at' => $this->input('started_at', now()->toDateTimeString())]);
    }
}
