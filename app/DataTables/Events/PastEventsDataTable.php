<?php

namespace App\DataTables\Events;

use App\Models\Event;
use Yajra\DataTables\Services\DataTable;

class RetiredEventsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('name', function (Event $event) {
                return $event->name;
            })
            ->editColumn('date', function (Event $event) {
                return $event->date->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', 'events.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Event::past();

        return $query;
    }
}
