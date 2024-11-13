<?php

declare(strict_types=1);

namespace App\Livewire\Referees;

use App\Builders\RefereeBuilder;
use App\Enums\RefereeStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasEmploymentDateColumn;
use App\Livewire\Concerns\Columns\HasFullNameColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Referee;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RefereesTable extends DataTableComponent
{
    use BaseTableTrait, HasEmploymentDateColumn, HasFullNameColumn, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'referees';

    protected string $routeBasePath = 'referees';

    public function builder(): RefereeBuilder
    {
        return Referee::query()
            ->with('currentEmployment')
            ->oldest('last_name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void
    {
        $this->addAdditionalSelects([
            'referees.first_name as first_name',
            'referees.last_name as last_name',
        ]);
    }

    public function columns(): array
    {
        return [
            $this->getDefaultFullNameColumn(),
            $this->getDefaultStatusColumn(),
            $this->getDefaultEmploymentDateColumn(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(RefereeStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            DateRangeFilter::make('Employment')
                ->config([
                    'allowInput' => true,
                    'altFormat' => 'F j, Y',
                    'ariaDateFormat' => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                    'placeholder' => 'Enter Date Range',
                    'locale' => 'en',
                ])
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate']),
        ];
    }
}
