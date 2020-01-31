<?php

namespace App\Http\Requests\TagTeams;

use App\Models\TagTeam;
use App\Rules\CanJoinTagTeam;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'wrestlers' => ['nullable', 'array', 'max:2'],
            'wrestlers.*' => [
                'bail', 'integer', Rule::exists('wrestlers'), new CanJoinTagTeam($this->input('started_at'))
            ],
        ];
    }
}
