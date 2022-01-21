<?php

namespace App\Http\Requests\Stables;

use App\Models\Stable;
use App\Rules\WrestlerJoinedStableInTagTeam;
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
        return $this->user()->can('create', Stable::class);
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
                'string',
                'min:3',
                Rule::unique('stables', 'name'),
            ],
            'started_at' => [
                'nullable',
                'string',
                'date',
            ],
            'wrestlers' => ['array'],
            'tag_teams' => ['array'],
            'wrestlers.*' => [
                'bail',
                'integer',
                'distinct',
                Rule::exists('wrestlers', 'id'),
            ],
            'tag_teams.*' => [
                'bail',
                'integer',
                'distinct',
                Rule::exists('tag_teams', 'id'),
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                if ($this->requestHasMembers() && $this->date('started_at')) {
                    $tagTeamIds = $this->collect('tag_teams');
                    $wrestlerIds = $this->collect('wrestlers');

                    if ($tagTeamIds->count() * 2 + $wrestlerIds->count() < 3) {
                        $validator->errors()->add(
                            '*',
                            'Stable must does not contain at least 3 members.'
                        );
                    }

                    if ($tagTeamIds->isNotEmpty()) {
                        $wrestlerIdsAddedFromTagTeams = collect();
                        $tagTeamIds->each(function ($tagTeamId) use (&$wrestlerIdsAddedFromTagTeams) {
                            $tagTeam = TagTeam::with('currentWrestlers')->whereKey($tagTeamId)->sole();

                            $tagTeamPartnerIds = $tagTeam->currentWrestlers->pluck('id');

                            $tagTeamPartnerIds->each(fn () => $wrestlerIdsAddedFromTagTeams->push($tagTeamPartnerIds));
                        });

                        $foundWrestlers = $wrestlerIdsAddedFromTagTeams->intersect($wrestlerIds);

                        if ($foundWrestlers) {
                            $validator->errors()->add(
                                'wrestlers',
                                'There are wrestlers that are added to the stable that were added from a tag team.'
                            );
                        }
                    }
                }
            }
        });
    }

    protected function requestHasMembers()
    {
        return $this->collect('tag_teams')->isNotEmpty() || $this->collect('wrestlers')->isNotEmpty();
    }
}
