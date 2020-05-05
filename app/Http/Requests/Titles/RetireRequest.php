<?php

namespace App\Http\Requests\Titles;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\CannotBeRetiredException;

class RetireRequest extends FormRequest
{
    public function authorize()
    {
        $title = $this->route('title');

        if ($this->user()->cannot('retire', $title)) {
            return false;
        }

        if (! $title->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        return true;
    }

    public function rules()
    {
        return [];
    }
}
