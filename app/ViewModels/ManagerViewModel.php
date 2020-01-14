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
}
