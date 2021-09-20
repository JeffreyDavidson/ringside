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
        return [
            'matches' => ['bail', 'present', 'array'],
            'matches.*' => ['bail', 'array'],
            'matches.*.match_type_id' => ['required', 'integer', Rule::exists('match_types', 'id')],
            'matches.*.referee_id' => ['required_with:matches.*', 'integer', Rule::exists('referees', 'id')],
            'matches.*.title_id' => ['nullable', 'integer', Rule::exists('titles', 'id')],
            'matches.*.competitors' => ['required_with:matches.*', 'array'],
            'matches.*.competitors.*' => ['integer', 'distinct', Rule::exists('wrestlers', 'id')],
        ];
    }
}
