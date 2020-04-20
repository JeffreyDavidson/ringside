<?php

namespace App\Http\Requests\Titles;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ConditionalActivationStartDateRule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $title = $this->route('title');

        return $this->user()->can('update', $title);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'ends_with:Title, Titles',
                Rule::unique('titles')->ignore($this->title->id)
            ],
            'introduced_at' => [new ConditionalActivationStartDateRule($this->route('title'))],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.ends_with' => 'A name must end with either Title or Titles',
        ];
    }
}
