<?php

declare(strict_types=1);

namespace App\Livewire\TagTeams;

use App\Builders\TagTeamBuilder;
use App\Enums\TagTeamStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasEmploymentDateColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasEmploymentDateFilter;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\TagTeam;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TagTeamsTable extends DataTableComponent
{
    use BaseTableTrait, HasEmploymentDateColumn, HasEmploymentDateFilter, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'tag_teams';

    protected string $routeBasePath = 'tag-teams';

    protected string $resourceName = 'tag teams';

    public function builder(): TagTeamBuilder
    {
        return TagTeam::query()
            ->with('currentEmployment')
            ->oldest('name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void {}

    public function columns(): array
    {
        return [
            Column::make(__('tag-teams.name'), 'name')
                ->sortable()
                ->searchable(),
            $this->getDefaultStatusColumn(),
            Column::make(__('tag-teams.partners'), 'partners'),
            Column::make(__('tag-teams.combined_weight'), 'combined_weight'),
            $this->getDefaultEmploymentDateColumn(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(TagTeamStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            $this->getDefaultEmploymentDateFilter(),
        ];
    }
}
