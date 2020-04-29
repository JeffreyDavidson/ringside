<?php

namespace App\ViewModels;

use App\Models\Title;
use Spatie\ViewModels\ViewModel;

class TitleViewModel extends ViewModel
{
    public $title;

    public function __construct(Title $title = null)
    {
        $this->title = $title ?? new Title;
        $this->title->introduced_at = optional($this->title->activated_at)->toDateTimeString();
    }
}
