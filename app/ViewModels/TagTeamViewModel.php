<?php

namespace App\ViewModels;

use App\Models\TagTeam;
use App\Models\Wrestler;
use Spatie\ViewModels\ViewModel;

class TagTeamViewModel extends ViewModel
{
    /** @var $tagTeam */
    public $tagTeam;

    /** @var $tagTeam */
    public $wrestlers;

    /**
     * Create a new tagTeam view model instance.
     *
     * @param App\Models\TagTeam|null $tagTeam
     */
    public function __construct(TagTeam $tagTeam = null)
    {
        $this->tagTeam = $tagTeam ?? new TagTeam;
        $this->tagTeam->started_at = optional($this->tagTeam->started_at)->toDateTimeString();
        $this->wrestlers = Wrestler::all();
    }
}
