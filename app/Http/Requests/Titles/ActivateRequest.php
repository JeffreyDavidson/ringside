<?php

namespace App\Http\Requests\Titles;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CannotBeActivatedException;

class ActivateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $title = $this->route('title');

        if (! $this->user->can('deactivate', $title)) {
            return false;
        }

        if (! $title->canBeActivated()) {
            throw new CannotBeActivatedException();
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
