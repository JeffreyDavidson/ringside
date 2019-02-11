<?php

namespace App\Http\Requests;

use App\Retirement;
use Illuminate\Foundation\Http\FormRequest;

class StoreRetirementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $wrestler = $this->route('wrestler');

        return $this->user()->can('create', Retirement::class) && !$wrestler->isRetired();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
