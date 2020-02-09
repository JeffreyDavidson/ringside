<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Rules\CanJoinTagTeam;
use App\Rules\CannotBeHindered;
use Illuminate\Validation\Rule;
use App\Rules\CannotBelongToTagTeam;
use App\Rules\CannotBeEmployedAfterDate;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => ['nullable', 'string', Rule::unique('tag_teams')->ignore($this->tag_team->id)],
            'signature_move' => ['nullable', 'string'],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
            'wrestlers' => ['required', 'array', 'max:2'],
            'wrestlers.*' => [
                'bail',
                'integer',
                Rule::exists('wrestlers'),
                new CannotBeEmployedAfterDate($this->input('started_at')),
                new CannotBeHindered,
                new CannotBelongToTagTeam,
            ]
        ];
    }
}
