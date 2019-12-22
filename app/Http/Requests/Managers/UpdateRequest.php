<?php

namespace App\Http\Requests\Managers;

use App\Models\Manager;
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
        $manager = $this->route('manager');

        return $this->user()->can('update', $manager);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s']
        ];

        if ($this->manager->currentEmployment) {
            if ($this->manager->currentEmployment->started_at) {
                $rules['started_at'][] = 'required';
            }

            if ($this->manager->currentEmployment->started_at && $this->manager->currentEmployment->started_at->isPast()) {
                $rules['started_at'][] = 'before_or_equal:' . $this->manager->currentEmployment->started_at->toDateTimeString();
            }
        }

        return $rules;
    }
}
