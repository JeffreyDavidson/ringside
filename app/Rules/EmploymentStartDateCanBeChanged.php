<?php

namespace App\Rules;

use App\Models\Contracts\Employable;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class EmploymentStartDateCanBeChanged implements Rule
{
    /** @var */
    protected $model;

    /**
     * Undocumented function.
     *
     * @param \App\Models\Contracts\Employable $employable
     */
    public function __construct(Employable $employable)
    {
        $this->model = $employable;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  string $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->model->canBeEmployed()) {
            return true;
        }

        if ($this->model->hasFutureEmployment()) {
            return true;
        }

        if ($this->model->started_at->eq($value)) {
            return true;
        }

        return false;
    }

    public function message()
    {
        return 'The :attribute field cannot be changed to a date different than '.$this->model->started_at->toDateTimeString().'.';
    }
}
