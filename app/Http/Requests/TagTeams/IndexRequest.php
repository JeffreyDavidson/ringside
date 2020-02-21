<?php

namespace App\Http\Requests\TagTeams;

use App\Enums\TagTeamStatus;
use App\Http\Requests\AjaxOnlyFormRequest;
use App\Models\TagTeam;

class IndexRequest extends AjaxOnlyFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', TagTeam::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => ['nullable', 'string', TagTeamStatus::rule()],
        ];

        $rules = $this->validateDateRange($rules, 'started_at');

        return $rules;
    }
}
