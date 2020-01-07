<?php

namespace App\DataTables;

use App\Models\Manager;
use App\Filters\ManagerFilters;
use Yajra\DataTables\DataTables;

class ManagersDataTable extends DataTables
{
    /** @var managerFilters */
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
    public function render($query)
    {
        return datatables($query)
            ->editColumn('started_at', function (Manager $manager) {
                return $manager->currentEmployment->started_at->format('Y-m-d H:s');
            })
            ->editColumn('name', function (Manager $manager) {
                return $manager->full_name;
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
    public function query($builder)
    {
        $query = Manager::with('employment');

        $this->managerFilters->apply($query);

        return $query;
    }
}
