<?php

namespace App\DataTables\Managers;

use App\Filters\ManagerFilters;
use App\Models\Manager;
use Yajra\DataTables\Services\DataTable;

class EmployedManagersDataTable extends DataTable
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
            ->editColumn('employed_at', function (Manager $manager) {
                return $manager->employed_at->toDateString();
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
            ->addColumn('action', function ($manager) {
                return view(
                    'managers.partials.action-cell',
                    [
                        'actions' => collect(['retire', 'employ', 'release', 'suspend', 'reinstate', 'injure', 'clearInjury']),
                        'model' => $manager
                    ]
                );
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Manager::employed()->withEmployedAtDate();

        $this->managerFilters->apply($query);

        return $query;
    }
}
