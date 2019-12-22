<?php

namespace App\Http\Requests\Managers;

use Illuminate\Foundation\Http\FormRequest;

class SuspendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('suspend', $this->route('manager'));
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
     * Determine if the manager can be suspended.
     *
     * @return boolean
     */
    public function canBeSuspended()
    {
        $manager = $this->route('manager');

        if ($manager->isPendingEmployment()) {
            return false;
        }

        if ($manager->isRetired()) {
            return false;
        }

        if ($manager->isInjured()) {
            return false;
        }

        if ($manager->isSuspended()) {
            return false;
        }

        return true;
    }
}
