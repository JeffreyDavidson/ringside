<?php

declare(strict_types=1);

namespace App\Http\Livewire\Datatable;

use Illuminate\Database\Query\Builder;

trait WithBulkActions
{
    /**
     * Undocumented variable.
     *
     * @var bool
     */
    public $selectPage = false;

    /**
     * Undocumented variable.
     *
     * @var bool
     */
    public $selectAll = false;

    /**
     * Undocumented variable.
     *
     * @var \Illuminate\Support\Collection<int, string>
     */
    public $selected;

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function bootWithSorting(): void
    {
        $this->selected = collect();
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function renderingWithBulkActions(): void
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function updatedSelected(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    /**
     * Undocumented function.
     *
     * @param  int  $value
     * @return void
     */
    public function updatedSelectPage(int $value): void
    {
        if ($value) {
            $this->selectPageRows();

            return;
        }

        $this->selectAll = false;
        $this->selected = collect();
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function selectPageRows(): void
    {
        $this->selected = $this->rows->pluck('id')->map(fn ($id) => (string) $id);
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function selectAll(): void
    {
        $this->selectAll = true;
    }

    /**
     * Undocumented function.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getSelectedRowsQueryProperty(): Builder
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll, fn ($query) => $query->whereKey($this->selected));
    }
}
