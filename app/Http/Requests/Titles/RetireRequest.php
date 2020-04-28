<?php

namespace App\Http\Requests\Titles;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CannotBeRetiredException;

class RetireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $title = $this->route('title');

        if (! $this->user->can('retire', $title)) {
            return false;
        }

        if (! $title->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        return true;
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
}
