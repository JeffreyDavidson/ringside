<?php

namespace App\Http\Requests;

use App\Models\MatchType;
use Dotenv\Exception\ValidationException;
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
            'matches' => ['required', 'array'],
            'matches.*.match_type_id' => ['required', 'integer', 'exists:match_types,id'],
            'matches.*' => ['required', 'array'],
            'matches.*.competitors' => ['required', 'array'],
            'matches.*.competitors.*' => ['required', 'array'],
            'matches.*.preview' => ['required', 'string'],
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->matches as $key => $match) {
                $type = MatchType::find($match['match_type_id']);

                if ($type === null) {
                    continue;
                }

                if ($match['competitors'] === null || !is_array($match['competitors'])) {
                    continue;
                }

                // foreach ($match['competitors'] as $side => $competitorType) {
                //     if (array_intersect(array_keys($competitorType), ['wrestlers']) !== array_keys($competitorType)) {
                //         $validator->errors()->add("matches.{$key}.competitors.*", 'Something is wrong with this field!');
                //     }
                // }

                if (!$this->matchHasCorrectSidesCount($match, $type)) {
                    $validator->errors()->add("matches.{$key}.competitors", 'Something is wrong with this field!');
                }

                if (!$this->matchHasCorrectCompetitorCount) {
                    $validator->errors()->add("matches.{$key}.competitors", 'Something is wrong with this field!');
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
