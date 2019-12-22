<?php

namespace App\Http\Requests\Managers;

use App\Models\Manager;
use Illuminate\Foundation\Http\FormRequest;

class RetireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('retire', Manager::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Determine if the manager can be retired.
     *
     * @return boolean
     */
    public function canBeRetired()
    {
        $manager = $this->route('manager');

        if ($manager->isPendingEmployment()) {
            return false;
        }

        if ($manager->isRetired()) {
            return false;
        }

        return true;
    }
}
