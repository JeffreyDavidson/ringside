<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Rules\CannotBeEmployedAfterDate;
use App\Rules\CannotBeHindered;
use App\Rules\CannotBelongToTagTeam;
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
            'name' => ['required', 'string', Rule::unique('tag_teams')],
            'signature_move' => ['nullable', 'string'],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
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
