<?php

namespace App\ViewModels;

use App\Models\Manager;
use Spatie\ViewModels\ViewModel;

class ManagerViewModel extends ViewModel
{
    /** @var $manager */
    public $manager;

    /**
     * Undocumented function
     *
     * @param App\Models\Manager $manager
     */
    public function __construct(Manager $manager = null)
    {
        $this->manager = $manager ?? new Manager;
        $this->manager->started_at = optional($this->manager->started_at ?? null)->toDateTimeString();
    }
}
