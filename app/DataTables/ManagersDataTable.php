<?php

namespace App\DataTables;

use App\Models\Manager;
use App\Filters\ManagerFilters;
use Yajra\DataTables\Services\DataTable;

class ManagersDataTable extends DataTable
{
    /** @var $managerFilters */
    private $managerFilters;

    /**
     * ManagerDataTable constructor.
     *
     * @param ManagerFilters $managerFilters
     */
    public function __construct(ManagerFilters $managerFilters)
    {
        $this->managerFilters = $managerFilters;
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
            ->editColumn('name', function (Manager $manager) {
                return $manager->full_name;
            })
            ->editColumn('started_at', function (Manager $manager) {
                return $manager->started_at->toDateString();
            })
            ->editColumn('status', function (Manager $manager) {
                return $manager->status->label();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $sql = "CONCAT(managers.first_name, ' ', managers.last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('action', 'managers.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Manager::whereHas('employments');

        $this->managerFilters->apply($query);

        return $query;
    }
}
