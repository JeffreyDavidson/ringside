<?php

namespace App\Http\Requests\Titles;

use App\Models\Title;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Title::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'ends_with:Title, Titles', 'unique:titles,name'],
            'introduced_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     * TODO: Find out if values can be concatenated with 'or' instead of a comma.
     */
    public function messages()
    {
        return [
            'name.ends_with' => 'The :attribute must end with :values.',
            'introduced_at.date_format' => 'The :attribute must be in the format of YYYY-MM-DD HH::MM:SS',
        ];
    }
}
