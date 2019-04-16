<?php

namespace App\Http\Requests;

use App\Models\MatchType;
use App\Rules\CorrectMatchSidesCount;
use Illuminate\Foundation\Http\FormRequest;

class StoreMatchesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $event = $this->route('event');

        return $this->user()->can('addMatches', $event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $event = $this->route('event');
        $date = $event->date->toDateTimeString();

        $rules = [
            'matches' => ['required', 'array', 'min:1'],
            'matches.*' => ['required', 'array'],
            'matches.*.match_type_id' => ['required', 'int', 'exists:match_types,id'],
            'matches.*.competitors' => ['required', 'array'],
            'matches.*.competitors.*.wrestlers' => ['required_without:matches.*.competitors.*.tagteams', 'array'],
            'matches.*.competitors.*.wrestlers.*' => ['required_with:matches.*.competitors.*.wrestlers', 'int', 'exists:wrestlers,id'],
            'matches.*.competitors.*.tagteams' => ['required_without:matches.*.competitors.*.wrestlers', 'array'],
            'matches.*.competitors.*.tagteams.*' => ['required_with:matches.*.competitors.*.tagteams', 'int', 'exists:tagteams,id'],
            'matches.*.preview' => ['required', 'string'],
            // 'matches.*' => [new CorrectMatchSidesCount],
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (is_array($this->matches)) {
                foreach ($this->matches as $key => $match) {
                    if (is_array($match)) {
                        if (!$type = MatchType::find($match['match_type_id'])) {
                            continue;
                        }

                        if (!is_array($match['competitors'])) {
                            continue;
                        }

                        if (!$this->matchHasCorrectSidesCount($match, $type)) {
                            $validator->errors()->add("matches.{$key}.competitors", 'Something is wrong with this field!');
                        }

                        if (!$this->matchHasCorrectCompetitorCount($match)) {
                            $validator->errors()->add("matches.{$key}.competitors", 'Something is wrong with this field!');
                        }
                    }
                }
            }
        });
    }

    /**
     * Undocumented function
     *
     * @param  array  $match
     * @return bool
     */
    private function matchHasCorrectSidesCount($match, $type)
    {
        return $type->number_of_sides === count($match['competitors']);
    }

    /**
     * Undocumented function
     *
     * @param  array  $match
     * @return bool
     */
    private function matchHasCorrectCompetitorCount($match)
    {
        $type = MatchType::find($match['match_type_id']);

        return $type->number_of_competitors === count($match['competitors']);
    }
}
