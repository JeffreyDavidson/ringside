<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Rules\CannotBeEmployedAfterDate;
use App\Rules\CannotBeHindered;
use App\Rules\CannotBelongToTagTeam;
use App\Rules\ConditionalEmploymentStartDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', TagTeam::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('tag_teams')->ignore($this->tag_team->id)],
            'signature_move' => ['nullable', 'string'],
            'started_at' => [new ConditionalEmploymentStartDateRule($this->route('tag_team'))],
            'wrestlers' => ['bail', 'required_with:started_at', 'array', 'max:2'],
            'wrestlers.*' => [
                'bail',
                'integer',
                'exists:wrestlers,id',
                new CannotBeEmployedAfterDate($this->input('started_at')),
                new CannotBeHindered,
                new CannotBelongToTagTeam,
            ],
        ];
    }
}
