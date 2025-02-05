<?php

declare(strict_types=1);

namespace App\Livewire\Stables\Tables;

use App\Builders\StableBuilder;
use App\Enums\StableStatus;
use App\Livewire\Base\Tables\BaseTableWithActions;
use App\Livewire\Concerns\Columns\HasFirstActivationDateColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Stable;
use App\View\Filters\FirstActivationFilter;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class StablesTable extends BaseTableWithActions
{
    use HasFirstActivationDateColumn, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'stables';

    protected string $routeBasePath = 'stables';

    protected string $resourceName = 'stables';

    /**
     * @return StableBuilder<Stable>
     */
    public function builder(): StableBuilder
    {
        return Stable::query()
            ->with('currentActivation')
            ->oldest('name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void {}

    /**
     * Undocumented function
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make(__('stables.name'), 'name')
                ->searchable(),
            $this->getDefaultStatusColumn(),
            $this->getDefaultFirstActivationDateColumn(),
        ];
    }

    /**
     * Undocumented function
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        /** @var array<string, string> $statuses */
        $statuses = collect(StableStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            FirstActivationFilter::make('Activation Date')->setFields('activations', 'stables_activations.started_at', 'stables_activations.ended_at'),
        ];
    }

    public function delete(Stable $stable): void
    {
        $this->deleteModel($stable);
    }
}
