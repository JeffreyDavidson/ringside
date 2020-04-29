<?php

namespace App\Http\Requests\Titles;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CannotBeActivatedException;

class ActivateRequest extends FormRequest
{
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

    public function rules()
    {
        return [];
    }
}
