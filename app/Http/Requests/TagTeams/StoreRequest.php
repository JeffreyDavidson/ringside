<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Rules\CannotBeEmployedAfterDate;
use App\Rules\CannotBeHindered;
use App\Rules\CannotBelongToMultipleEmployedTagTeams;
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
        return $this->user()->can('create', TagTeam::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', Rule::unique('tag_teams', 'name')],
            'signature_move' => ['nullable', 'string'],
            'started_at' => ['nullable', 'string', 'date'],
            'wrestlers' => ['nullable', 'array', 'required_with:signature_move'],
            'wrestlers.*' => [
                'nullable',
                'bail',
                'integer',
                'distinct',
                Rule::exists('wrestlers', 'id'),
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
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isEmpty()) {
                foreach ($this->input('wrestlers') as $wrestlerId) {
                    $wrestler = Wrestler::query()
                        ->with(['currentTagTeam', 'currentEmployment', 'futureEmployment'])
                        ->whereKey($wrestlerId)
                        ->sole();

                    $cannotBeEmployedAfterDateRuleResult = (new CannotBeEmployedAfterDate($wrestler, $this->input('started_at')))->passes();

                    if (! $cannotBeEmployedAfterDateRuleResult) {
                        $validator->addFailure('wrestlers', CannotBeEmployedAfterDate::class);
                    }

                    $cannotBeHinderedRuleResult = (new CannotBeHindered($wrestler))->passes();

                    if (! $cannotBeHinderedRuleResult) {
                        $validator->addFailure('wrestlers', CannotBeHindered::class);
                    }

                    $cannotBelongToMultipleEmployedTagTeamRuleResult = (new CannotBelongToMultipleEmployedTagTeams)->passes();

                    if (! $cannotBelongToMultipleEmployedTagTeamRuleResult) {
                        $validator->addFailure('wrestlers', CannotBelongToMultipleEmployedTagTeams::class);
                    }
                }
            }
        });
    }
}
