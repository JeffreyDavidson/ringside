<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjaxOnlyFormRequest extends FormRequest
{
    public function validateResolved()
    {
        if ($this->ajax()) {
            parent::validateResolved();
        }
    }

    protected function validateDateRange(array $currentRules, string $field)
    {
        return array_merge_recursive($currentRules, [
            "{$field}"       => ['nullable', 'array'],
            "{$field}_start" => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
            "{$field}_end"   => [
                'nullable',
                "required_with:{$field}_start",
                'string',
                'date_format:Y-m-d H:i:s',
                "after:{$field}_start",
            ],
        ]);
    }
}
