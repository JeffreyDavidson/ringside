<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Models\TagTeam;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PreviousManagersTable extends DataTableComponent
{
    /**
     * Tag Team to use for component.
     */
    public TagTeam $tagTeam;

    /**
     * Set the Tag Team to be used for this component.
     */
    public function mount(TagTeam $tagTeam): void
    {
        $this->tagTeam = $tagTeam;
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('managers.name'), 'name'),
            Column::make(__('managers.date_hired'), 'date_hired'),
            Column::make(__('managers.date_fired'), 'date_fired'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->tagTeam
            ->previousManagers()
            ->addSelect(
                DB::raw("CONCAT(managers.first_name,' ', managers.last_name) AS full_name"),
            );

        $previousManagers = $query->paginate();

        return view('livewire.tag-teams.previous-managers.previous-managers-list', [
            'previousManagers' => $previousManagers,
        ]);
    }
}
