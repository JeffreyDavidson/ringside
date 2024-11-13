<?php

declare(strict_types=1);

namespace App\Livewire\Concerns\Columns;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait HasEmploymentDateColumn
{
    protected function getDefaultEmploymentDateColumn(): Column
    {
        return Column::make(__('employments.start_date'), 'start_date')
            ->label(fn ($row, Column $column) => $row->currentEmployment?->started_at->format('Y-m-d') ?? 'TBD');
    }
}
