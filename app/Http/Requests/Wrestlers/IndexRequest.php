<?php

namespace App\Http\Requests\Wrestlers;

use App\Enums\WrestlerStatus;
use App\Http\Requests\AjaxOnlyFormRequest;
use App\Models\Wrestler;

class IndexRequest extends AjaxOnlyFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', Wrestler::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => ['nullable', 'string', WrestlerStatus::rule()],
        ];

        $rules = $this->validateDateRange($rules, 'started_at');

        return $rules;
    }
}
