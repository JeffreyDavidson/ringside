<?php

declare(strict_types=1);

namespace App\Http\Livewire\Datatable;

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
     * @var array
     */
    public $selected = [];

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function renderingWithBulkActions()
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
    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    /**
     * Undocumented function.
     *
     * @param [type] $value
     * @return void
     */
    public function updatedSelectPage($value)
    {
        if ($value) {
            return $this->selectPageRows();
        }

        $this->selectAll = false;
        $this->selected = [];
    }

    public function selectPageRows()
    {
        $this->selected = $this->rows->pluck('id')->map(fn ($id) => (string) $id);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function selectAll()
    {
        $this->selectAll = true;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll, fn ($query) => $query->whereKey($this->selected));
    }
}
