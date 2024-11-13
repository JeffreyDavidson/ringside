<?php

declare(strict_types=1);

namespace App\Livewire\Concerns\Columns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\Views\Column;

trait HasFullNameColumn
{
    protected function getDefaultFullNameColumn(): Column
    {
        return Column::make(__('core.full_name'))
            ->label(fn ($row, Column $column) => ucwords($row->first_name.' '.$row->last_name))
            ->sortable()
            ->searchable(function (Builder $query, $searchTerm) {
                $query->whereLike('first_name', "%{$searchTerm}%")
                    ->orWhereLike('last_name', "%{$searchTerm}%")
                    ->orWhereLike(DB::raw("CONCAT(first_name, ' ', last_name)"), "%{$searchTerm}%");
            });
    }
}
