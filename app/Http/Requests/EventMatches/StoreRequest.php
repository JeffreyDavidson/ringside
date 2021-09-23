<?php

namespace App\Http\Requests\EventMatches;

use App\Models\EventMatch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', EventMatch::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->all());

        return [
            'matches' => ['bail', 'required', 'array', 'min:1'],
            'matches.*' => ['bail', 'required', 'array'],
            'matches.*.match_type_id' => ['required_with:matches.*', 'integer', Rule::exists('match_types', 'id')],
            'matches.*.referees' => ['required_with:matches.*', 'array'],
            'matches.*.referees.*' => ['required_with:matches.*', 'integer', 'distinct', Rule::exists('referees', 'id')],
            'matches.*.titles' => ['nullable', 'array'],
            'matches.*.titles.*' => ['integer', 'distinct', Rule::exists('titles', 'id')],
            'matches.*.competitors' => ['required_with:matches.*', 'array'],
            'matches.*.competitors.*' => ['integer', 'distinct', Rule::exists('wrestlers', 'id')],
        ];
    }
}
