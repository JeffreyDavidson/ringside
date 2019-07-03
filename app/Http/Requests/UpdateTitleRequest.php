<?php

namespace App\Http\Requests;

use App\Models\TItle;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTitleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Title::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'ends_with:Title, Titles', Rule::unique('titles')->ignore($this->title->id)],
            'introduced_at' => ['required', 'date_format:Y-m-d H:i:s'],
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
