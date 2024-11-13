<?php

declare(strict_types=1);

namespace App\Livewire\Venues;

use App\Livewire\Concerns\BaseTableTrait;
use App\Models\Venue;
use Illuminate\Contracts\View\View;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VenuesTable extends DataTableComponent
{
    use BaseTableTrait;

    protected string $databaseTableName = 'venues';

    protected string $routeBasePath = 'venues';

    public function configure(): void
    {
    }

    public function columns(): array
    {
        return [
            Column::make(__('venues.name'), 'name'),
            Column::make(__('venues.address'), 'address'),
            Column::make(__('venues.city'), 'city'),
            Column::make(__('venues.state'), 'state'),
            Column::make(__('venues.zipcode'), 'zipcode'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function render(): View
    {
        $query = Venue::query()
            ->oldest('name');

        $venues = $query->paginate();

        return view('livewire.venues.venues-list', [
            'venues' => $venues,
        ]);
    }
}
