<?php

namespace App\DataTables;

use App\Filters\RefereeFilters;
use App\Models\Referee;
use Yajra\DataTables\Services\DataTable;

class RefereesDataTable extends DataTable
{
    /** @var $refereeFilters */
    private $refereeFilters;

    /**
     * RefereeDataTable constructor.
     *
     * @param RefereeFilters $refereeFilters
     */
    public function __construct(RefereeFilters $refereeFilters)
    {
        $this->refereeFilters = $refereeFilters;
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
            ->editColumn('name', function (Referee $referee) {
                return $referee->full_name;
            })
            ->editColumn('started_at', function (Referee $referee) {
                return $referee->started_at->toDateString();
            })
            ->editColumn('status', function (Referee $referee) {
                return $referee->status->label();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $sql = "CONCAT(referees.first_name, ' ', referees.last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('action', 'referees.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Referee::whereHas('employments');

        $this->refereeFilters->apply($query);

        return $query;
    }
}
