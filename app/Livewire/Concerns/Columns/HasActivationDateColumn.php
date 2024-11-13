<?php

declare(strict_types=1);

namespace App\Livewire\Concerns\Columns;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait HasActivationDateColumn
{
    protected function getDefaultActivationDateColumn(): Column
    {
        return Column::make(__('activations.start_date'), 'start_date')
            ->label(fn ($row, Column $column) => $row->currentActivation?->started_at->format('Y-m-d') ?? 'TBD');
    }
}
