<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers;

use App\Builders\TitleBuilder;
use App\Livewire\Concerns\ExtraTableTrait;
use App\Models\Title;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WrestlerTitleChampionshipsTable extends DataTableComponent
{
    use ExtraTableTrait;

    protected string $databaseTableName = 'wrestler_championships';

    /**
     * WrestlerId to use for component.
     */
    public int $wrestlerId;

    public function configure(): void {}

    public function builder(): TitleBuilder
    {
        return Title::withWhereHas('wrestlers', function ($query) {
            $query->where('wrestler_id', $this->wrestlerId);
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('titles.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('titles.previous_champion'), '')
                ->searchable(),
            Column::make(__('titles.days_held'), '')
                ->sortable(),
            Column::make(__('employments.won_at'), 'won_at')
                ->sortable(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = $this->wrestler
            ->previousTitleChampionships()
            ->with('title')
            ->addSelect(
                'title_championships.title_id',
                'title_championships.won_at',
                'title_championships.lost_at',
                DB::raw('DATEDIFF(COALESCE(lost_at, NOW()), won_at) AS days_held_count')
            );

        $query = $this->applySorting($query);

        $previousTitleChampionships = $query->paginate();

        return view('livewire.wrestlers.previous-title-championships.previous-title-championships-list', [
            'previousTitleChampionships' => $previousTitleChampionships,
        ]);
    }
}
