<?php

namespace App\DataTables\Events;

use App\Filters\EventFilters;
use App\Models\Event;
use Yajra\DataTables\Services\DataTable;

class ScheduledEventsDataTable extends DataTable
{
    /** @var $eventFilters */
    private $eventFilters;

    /**
     * EventDataTable constructor.
     *
     * @param EventFilters $eventFilters
     */
    public function __construct(EventFilters $eventFilters)
    {
        $this->eventFilters = $eventFilters;
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('scheduled_at', function (Event $event) {
                return $event->scheduled_at->toDateString();
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
        $query = Event::scheduled();

        $this->eventFilters->apply($query);

        return $query;
    }
}
