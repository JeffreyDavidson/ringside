<?php

namespace App\DataTables\Wrestlers;

use App\Filters\WrestlerFilters;
use App\Models\Wrestler;
use Yajra\DataTables\Services\DataTable;

class EmployedWrestlersDataTable extends DataTable
{
    /** @var $wrestlerFilters */
    private $wrestlerFilters;

    /**
     * WrestlerDataTable constructor.
     *
     * @param WrestlerFilters $wrestlerFilters
     */
    public function __construct(WrestlerFilters $wrestlerFilters)
    {
        $this->wrestlerFilters = $wrestlerFilters;
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
            ->editColumn('employed_at', function (Wrestler $wrestler) {
                return $wrestler->employed_at->toDateString();
            })
            ->editColumn('status', function (Wrestler $wrestler) {
                return $wrestler->status->label();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($wrestler) {
                return view(
                    'wrestlers.partials.action-cell',
                    [
                        'actions' => collect(['retire', 'employ', 'release', 'suspend', 'reinstate', 'injure', 'clearInjury']),
                        'model' => $wrestler
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
        $query = Wrestler::employed()->withEmployedAtDate();

        $this->wrestlerFilters->apply($query);

        return $query;
    }
}
