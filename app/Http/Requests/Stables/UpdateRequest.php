<?php

namespace App\Http\Requests\Stables;

use App\Models\Stable;
use App\Rules\StableHasEnoughMembers;
use App\Rules\TagTeamCanJoinStable;
use App\Rules\WrestlerCanJoinStable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Stable::class);
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
                Rule::unique('stables')->ignore($this->route()->parameter('stable')->id),
            ],
            'started_at' => [
                'nullable',
                Rule::requiredIf(fn () => ! $this->route()->parameter('stable')->isUnactivated()),
                'string',
                'date',
            ],
            'wrestlers' => ['array'],
            'tag_teams' => ['array'],
            'wrestlers.*' => [
                'bail ',
                'integer',
                'distinct',
                Rule::exists('wrestlers', 'id'),
                new WrestlerCanJoinStable($this->route()->parameter('stable'), $this->date('started_at')),
            ],
            'tag_teams.*' => [
                'bail',
                'integer',
                'distinct',
                Rule::exists('tag_teams', 'id'),
                new TagTeamCanJoinStable($this->route()->parameter('stable'), $this->date('started_at')),
            ],
        ];
    }

    /**
     * Perform additional validation.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                $stable = $this->route()->parameter('stable');

                if ($stable->isCurrentlyActivated()
                    && $stable->currentActivation->started_at->ne($this->input('activated_at'))
                ) {
                    $validator->errors()->add(
                        'activated_at',
                        "{$stable->name} is currently activated and the activation date cannot be changed."
                    );
                }

                $tagTeamsCountFromRequest = count($this->input('tag_teams'));
                $wrestlersCountFromRequest = count($this->input('wrestlers'));

                $totalTagTeamsCount = count($this->stable->currentTagTeams) + $tagTeamsCountFromRequest;
                $totalWrestlersCount = count($this->stable->currentWrestlers) + $wrestlersCountFromRequest;

                if ($stable->isCurrentlyActivated()) {
                    if ($totalTagTeamsCount * 2 + $totalWrestlersCount < 3) {
                        $validator->errors()->add(
                            '*',
                            "{$stable->name} does not contain at least 3 members."
                        );
                    }
                }
            }
        });
    }
}
