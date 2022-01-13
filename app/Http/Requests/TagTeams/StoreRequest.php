<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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

                    if ($this->wrestler->isCurrentlyEmployed()
                        && ! $wrestler->currentEmployment->started_at->ne($this->startedAt)
                    ) {
                        $validator->errors()->add(
                            'wrestlers',
                            "{$wrestler->name} is currently employed and the employment date cannot be changed."
                        );
                    }

                    if ($wrestler->hasFutureEmployment() && ! $wrestler->futureEmployment->started_at->lt(now())) {
                        $validator->errors()->add(
                            'wrestlers',
                            "{$wrestler->name} has a future employment scheduled after the current date."
                        );
                    }

                    if ($wrestler->isUnemployed() || $wrestler->isBookable()) {
                        $validator->errors()->add(
                            'wrestlers',
                            $wrestler->name.' is not allowed to join this tag team.'
                        );
                    }

                    if (null !== $wrestler->currentTagTeam) {
                        $validator->errors()->add('wrestlers', $wrestler->name.' is already a part of a tag team.');
                    }
                }
            }
        });
    }
}
