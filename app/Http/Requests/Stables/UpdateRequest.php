<?php

namespace App\Http\Requests\Stables;

use App\Models\Stable;
use App\Rules\ActivationStartDateCanBeChanged;
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
                Rule::unique('stables')->ignore($this->route->param('stable')->id),
            ],
            'started_at' => [
                'nullable',
                Rule::requiredIf(fn () => ! $this->route->param('stable')->isUnactivated()),
                'string',
                'date',
                new ActivationStartDateCanBeChanged($this->route->param('stable')),
            ],
            'wrestlers' => ['array'],
            'tag_teams' => ['array'],
            'wrestlers.*' => [
                'bail ',
                'integer',
                'distinct',
                Rule::exists('wrestlers', 'id'),
                new WrestlerCanJoinStable($this->route->param('stable'), $this->date('started_at')),
            ],
            'tag_teams.*' => [
                'bail',
                'integer',
                'distinct',
                Rule::exists('tag_teams', 'id'),
                new TagTeamCanJoinStable($this->route->param('stable'), $this->date('started_at')),
            ],
        ];
    }

    /**
     * Perform additional validation.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                $membersWereAdded = count($this->input('tag_teams')) > 0 || count($this->input('wrestlers')) > 0;
                if ($membersWereAdded) {
                    $result = (new StableHasEnoughMembers(
                        $this->input('tag_teams'),
                        $this->input('wrestlers')
                    ))->passes();

                    if (! $result) {
                        $validator->addFailure('wrestlers', StableHasEnoughMembers::class);
                        $validator->addFailure('tag_teams', StableHasEnoughMembers::class);
                    }
                }
            }
        });
    }
}
