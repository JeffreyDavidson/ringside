<?php

namespace App\Http\Requests\Managers;

use App\Models\Manager;
use App\Rules\EmploymentStartDateCanBeChanged;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Manager::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3'],
            'last_name' => ['required', 'string', 'min:3'],
            'started_at' => array_merge($this->started_at ? ['string', 'date_format:Y-m-d H:i:s'] : [], [
                new EmploymentStartDateCanBeChanged($this->route('manager'))
            ])
        ];
    }
}
