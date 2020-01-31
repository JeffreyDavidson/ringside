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
        return [
            'status' => [
                'nullable',
                'string',
                TagTeamStatus::rule(),
            ],
            'started_at' => [
                'nullable',
                'array',
            ],
            'started_at.0' => [
                'nullable',
                'string',
                'date_format:Y-m-d H:i:s',
            ],
            'started_at.1' => [
                'nullable',
                'required_with:started_at.0',
                'string',
                'date_format:Y-m-d H:i:s',
                'after:started_at.0',
            ],
        ];
    }
}
