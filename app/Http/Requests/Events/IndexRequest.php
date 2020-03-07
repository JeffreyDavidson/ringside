<?php

namespace App\Http\Requests\Events;

use App\Enums\EventStatus;
use App\Http\Requests\AjaxOnlyFormRequest;
use App\Models\Event;

class IndexRequest extends AjaxOnlyFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', Event::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'status' => ['nullable', 'string', EventStatus::rule()],
        ];

        $rules = $this->validateDateRange($rules, 'date');

        return $rules;
    }
}
