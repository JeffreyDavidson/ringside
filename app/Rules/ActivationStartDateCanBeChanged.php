<?php

namespace App\Rules;

use App\Models\Contracts\Activatable;
use Illuminate\Contracts\Validation\Rule;

class ActivationStartDateCanBeChanged implements Rule
{
    /**
     * @var \App\Models\Contracts\Activatable
     */
    protected $model;

    /**
     * @param \App\Models\Contracts\Activatable $activatable
     */
    public function __construct(Activatable $activatable)
    {
        $this->model = $activatable;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->model->isUnactivated()) {
            return true;
        }

        if ($this->model->hasFutureActivation()) {
            return true;
        }

        return false;
    }

    public function message()
    {
        return 'The :attribute field cannot be changed to a date after its been introduced.';
    }
}
