<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Builders\TagTeamBuilder;
use App\Livewire\Concerns\BaseTableTrait;
use App\Models\TagTeam;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TagTeamsTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'tag_teams';

    protected string $routeBasePath = 'tag-teams';

    public function builder(): TagTeamBuilder
    {
        return TagTeam::query()
            ->oldest('name');
    }

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('tag-teams.name'), 'name'),
            Column::make(__('tag-teams.status'), 'status')
                ->view('components.tables.columns.status-column'),
            Column::make(__('tag-teams.partners'), 'partners'),
            Column::make(__('tag-teams.combined_weight'), 'combined_weight'),
            Column::make(__('employments.start_date'), 'start_date'),
        ];
    }
}
