<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Models\Manager;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ManagersList extends Component
{
    /**
     * @var array<int>
     */
    public array $selectedManagerIds = [];

    /**
     * @var array<int>
     */
    public array $managerIdsOnPage = [];

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Manager::query()
            ->oldest('last_name');

        $managers = $query->paginate();

        $this->managerIdsOnPage = $managers->map(fn (Manager $manager) => (string) $manager->id)->toArray();

        return view('livewire.managers.managers-list', [
            'managers' => $managers,
        ]);
    }
}
