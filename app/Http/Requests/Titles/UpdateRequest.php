<?php

namespace App\Http\Requests\Titles;

use App\Rules\ConditionalActivationStartDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        $title = $this->route('title');

        return $this->user()->can('update', $title);
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:3',
                'ends_with:Title,Titles',
                Rule::unique('titles')->ignore($this->title->id)
            ],
            'activated_at' => [
                new ConditionalActivationStartDateRule($this->route('title'))
            ],
        ];
    }
}
