<?php

namespace App\Http\Livewire\Matches;

use App\Http\Livewire\BaseComponent;
use App\Models\EventMatch;
use App\Models\MatchType;

class MatchForm extends BaseComponent
{
    public $match;

    public $subViewToUse;

    public $matchTypeId;

    public function mount(EventMatch $match)
    {
        $this->match = $match;
    }

    public function updatedMatchTypeId()
    {
        $matchTypeSlug = MatchType::find($this->matchTypeId)->slug;

        return $this->subViewToUse = 'matches.types.'.$matchTypeSlug;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.matches.form', [
            'match' => $this->match,
        ]);
    }
}
