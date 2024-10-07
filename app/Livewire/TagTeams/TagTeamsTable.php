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

    protected string $formModalPath = 'tag-teams.modals.form-modal';

    protected string $deleteModalPath = 'tag-teams.modals.delete-modal';

    protected string $baseModel = 'tag-team';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.tag-teams.table-pre',
        ]);

        $this->setSearchPlaceholder('Search tag teams');
    }

    public function builder(): TagTeamBuilder
    {
        return TagTeam::query()
            ->with('latestEmployment');
    }

    public function columns(): array
    {
        return [
            Column::make(__('tag-teams.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('tag-teams.status'), 'status')
                ->view('tables.columns.status'),
            Column::make(__('tag-teams.combined_weight'), 'combined_weight'),
            Column::make(__('employments.start_date'), 'latestEmployment.started_at')
                ->label(fn ($row, Column $column) => $row->latestEmployment?->started_at->format('Y-m-d') ?? 'TBD'),
        ];
    }
}
