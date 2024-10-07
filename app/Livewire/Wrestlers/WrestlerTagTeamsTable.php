<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\TagTeamBuilder;
use App\Livewire\Concerns\ExtraTableTrait;
use App\Models\TagTeam;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlerTagTeamsTable extends DataTableComponent
{
    use ExtraTableTrait;

    /**
     * WrestlerId to use for component.
     */
    public int $wrestlerId;

    protected string $databaseTableName = 'tag_teams';

    public function configure(): void
    {
        $this->addAdditionalSelects([
            'tag_team_wrestler.joined_at as joined_at',
            'tag_team_wrestler.left_at as left_at',
        ]);
    }

    public function builder(): TagTeamBuilder
    {
        // dd(TagTeam::withWhereHas('wrestlers', function ($query) {
        //     $query->where('wrestler_id', $this->wrestlerId);
        // })->toRawSql());
        return TagTeam::withWhereHas('wrestlers', function ($query) {
            $query->select('joined_at', 'left_at')->where('wrestler_id', $this->wrestlerId);
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('tag-teams.name'))
                ->sortable(),
            Column::make(__('tag-teams.joined_at'), 'tag_team_wrestler.joined_at')
                ->label(fn ($row, Column $column) => $row->pivot->joined_at->format('Y-m-d') ?? 'Current'),
            Column::make(__('tag-teams.left_at'), 'tag_team_wrestler.left_at')
                ->label(fn ($row, Column $column) => $row->pivot->left_at->format('Y-m-d') ?? 'Current'),
        ];
    }
}
