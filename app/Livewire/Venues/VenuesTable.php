<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VenuesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'venues';
    protected string $routeBasePath = 'venues';
    protected string $formModalPath = 'venues.modals.form-modal';
    protected string $deleteModalPath = 'venues.modals.delete-modal';
    protected string $baseModel = 'venue';

    public function configure(): void
    {
        $this->setConfigurableAreas([
            'before-wrapper' => 'components.venues.table-pre',
        ]);

        $this->setSearchPlaceholder('Search venues');
    }

    public function builder(): Builder
    {
        return Venue::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('venues.name'), 'name')
                ->sortable()
                ->searchable(),
            Column::make(__('venues.street_address'), 'street_address'),
            Column::make(__('venues.city'), 'city'),
            Column::make(__('venues.state'), 'state'),
            Column::make(__('venues.zipcode'), 'zipcode'),
        ];
    }
}
