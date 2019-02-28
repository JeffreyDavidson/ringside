<?php

namespace App\Http\Requests;

use App\Stable;
use App\Rules\CanJoinStable;
use Illuminate\Foundation\Http\FormRequest;

class StoreStableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Stable::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $wrestler_rules = '';
        $tagteam_rules = '';

        if (count($this->input('tagteams') <= 1) && count($this->input('wrestlers') < 3)) {
            $wrestlersNeeded = 3 - ($this->input('tagteams') * 2);
            $wrestler_rules = 'min:'.$wrestlersNeeded;
        } elseif (count($this->input('wrestlers') < 3)) {
            $tagteamsNeeded = ceil(3 - ($this->input('wrestlers') / 2));
            $tagteam_rules = 'min:'.$tagteamsNeeded;
        }

        return [
            'name' => ['required'],
            'started_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'wrestlers' => Rule::requiredIf(function () {
                return count($this->tagteams) <= 1;
            }),
            'tagteams' => Rule::requiredIf(function () {
                return count($this->wrestlers) <= 2;
            }),
            'wrestlers' => ['array', $wrestler_rules],
            'wrestlers.*' => ['bail', 'integer', 'exists:wrestlers,id', new CanJoinStable],
            'tagteams' => ['array', $tagteam_rules],
            'tagteams.*' => ['bail', 'integer', 'exists:tagteams,id', new CanJoinStable],
        ];
    }
}
