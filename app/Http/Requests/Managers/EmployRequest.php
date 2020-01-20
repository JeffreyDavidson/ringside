<?php

namespace App\Http\Requests\Managers;

use App\Models\Manager;
use App\Rules\WithoutActiveEmployment;
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
        return $this->user()->can('employ', Manager::class);
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
                new WithoutActiveEmployment($this->route('manager')),
            ],
        ];
    }

    /*
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge(['started_at' => $this->input('started_at', now()->toDateTimeString())]);
    }
}
