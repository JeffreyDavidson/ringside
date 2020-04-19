<?php

namespace App\ViewModels;

use App\Models\Title;
use Spatie\ViewModels\ViewModel;

class TitleViewModel extends ViewModel
{
    /** @var $title */
    public $title;

    /**
     * Create a new title view model instance.
     *
     * @param App\Models\Title|null $title
     */
    public function __construct(Title $title = null)
    {
        $this->title = $title ?? new Title;
        $this->title->introduced_at = optional($this->title->activated_at)->toDateTimeString();
    }
}
