<?php

declare(strict_types=1);

namespace App\Livewire\Managers;

use App\Builders\ManagerBuilder;
use App\Enums\ManagerStatus;
use App\Livewire\Concerns\BaseTableTrait;
use App\Livewire\Concerns\Columns\HasFirstEmploymentDateColumn;
use App\Livewire\Concerns\Columns\HasFullNameColumn;
use App\Livewire\Concerns\Columns\HasStatusColumn;
use App\Livewire\Concerns\Filters\HasFirstEmploymentDateFilter;
use App\Livewire\Concerns\Filters\HasStatusFilter;
use App\Models\Manager;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ManagersTable extends DataTableComponent
{
    use BaseTableTrait, HasFirstEmploymentDateColumn, HasFirstEmploymentDateFilter, HasFullNameColumn, HasStatusColumn, HasStatusFilter;

    protected string $databaseTableName = 'managers';

    protected string $routeBasePath = 'managers';

    protected string $resourceName = 'managers';

    public function builder(): ManagerBuilder
    {
        return Manager::query()
            ->with('firstEmployment')
            ->oldest('last_name')
            ->when($this->getAppliedFilterWithValue('Status'), fn ($query, $status) => $query->where('status', $status));
    }

    public function configure(): void
    {
        $this->addAdditionalSelects([
            'managers.first_name as first_name',
            'managers.last_name as last_name',
        ]);
    }

    public function columns(): array
    {
        return [
            $this->getDefaultFullNameColumn(),
            $this->getDefaultStatusColumn(),
            $this->getDefaultFirstEmploymentDateColumn(),
        ];
    }

    public function filters(): array
    {
        $statuses = collect(ManagerStatus::cases())->pluck('name', 'value')->toArray();

        return [
            $this->getDefaultStatusFilter($statuses),
            $this->getDefaultFirstEmploymentDateFilter(),
        ];
    }
}
