<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Models\Manager;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ManagersList extends Component
{
    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Manager::query()
            ->oldest('last_name');

        $managers = $query->paginate();

        return view('livewire.managers.managers-list', [
            'managers' => $managers,
        ]);
    }
}
