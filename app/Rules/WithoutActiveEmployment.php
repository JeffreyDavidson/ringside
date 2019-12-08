<?php

namespace App\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Validation\Rule;

class WithoutActiveEmployment implements Rule
{
    /** @var $model */
    protected $model;
    
    /**
     * Undocumented function
     *
     * @param Model $employable
     */
    public function __construct(Model $employable)
    {
        $this->model = $employable;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $attribute
     * @param [type] $value
     * @return void
     */
    public function passes($attribute, $value = null)
    {
        if ($value === null) {
            return true;
        }
        
        $activeEmployment = $this->model->currentEmployment;

        if (!$activeEmployment || $activeEmployment->started_at->eq($value) || $activeEmployment->started_at->isFuture()) {
            return true;
        }
        
        return false;
    }

    public function message()
    {
        return 'Active employment already exists';
    }
}
