<?php

namespace App\ViewModels;

use App\Models\Manager;
use Spatie\ViewModels\ViewModel;

class ManagerViewModel extends ViewModel
{
    public function __construct(Manager $manager = null)
    {
        $this->manager = $manager ?? new Manager;
    }

    public function started_at()
    {
        return optional($this->manager->employment->started_at ?? null)->toDateTimeString();
    }
}
