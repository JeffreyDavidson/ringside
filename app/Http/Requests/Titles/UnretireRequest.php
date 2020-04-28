<?php

namespace App\Http\Requests\Titles;

use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Http\FormRequest;

class UnretireRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $title = $this->route('title');

        if (! $this->user->can('unretire', $title)) {
            return false;
        }

        if (! $title->isRetired()) {
            throw new CannotBeUnretiredException();
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
