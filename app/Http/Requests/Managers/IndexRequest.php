<?php

namespace App\Http\Requests\Managers;

use App\Enums\ManagerStatus;
use App\Http\Requests\AjaxOnlyFormRequest;
use App\Models\Manager;

class IndexRequest extends AjaxOnlyFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', Manager::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => ['nullable', 'string', ManagerStatus::rule()],
        ];

        $rules = $this->validateDateRange($rules, 'started_at');

        dd($rules);

        return $rules;
    }
}
