<?php

namespace App\Http\Requests\Referees;

use App\Models\Referee;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmploymentStartDateCanBeChanged;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Referee::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'started_at' => [
                'sometimes',
                'string',
                'date_format:Y-m-d H:i:s',
                new EmploymentStartDateCanBeChanged($this->route('referee'))
            ]
        ];
    }
}
