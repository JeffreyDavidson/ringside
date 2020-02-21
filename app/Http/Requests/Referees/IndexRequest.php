<?php

namespace App\Http\Requests\Referees;

use App\Enums\RefereeStatus;
use App\Http\Requests\AjaxOnlyFormRequest;
use App\Models\Referee;

class IndexRequest extends AjaxOnlyFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', Referee::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => ['nullable', 'string', RefereeStatus::rule()],
        ];

        $rules = $this->validateDateRange($rules, 'started_at');

        return $rules;
    }
}
