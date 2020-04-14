<?php

namespace App\DataTables\Referees;

use App\Filters\RefereeFilters;
use App\Models\Referee;
use Yajra\DataTables\Services\DataTable;

class EmployedRefereesDataTable extends DataTable
{
    /** @var $refereeFilters */
    private $refereeFilters;

    /**
     * RefereesDataTable constructor.
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
            ->editColumn('employed_at', function (Referee $referee) {
                return $referee->employed_at->toDateString();
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
            ->addColumn('action', function ($referee) {
                return view(
                    'referees.partials.action-cell',
                    [
                        'actions' => collect(['retire', 'employ', 'release', 'suspend', 'reinstate', 'injure', 'clearInjury']),
                        'model' => $referee
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
        $query = Referee::employed()->withEmployedAtDate();

        $this->refereeFilters->apply($query);

        return $query;
    }
}
