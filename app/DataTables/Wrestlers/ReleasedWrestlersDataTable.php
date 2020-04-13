<?php

namespace App\DataTables\Wrestlers;

use App\Models\Wrestler;
use Yajra\DataTables\Services\DataTable;

class ReleasedWrestlersDataTable extends DataTable
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
            ->editColumn('released_at', function (Wrestler $wrestler) {
                return $wrestler->previousEmployment()->ended_at->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', 'wrestlers.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Wrestler::query()
            ->released()
            ->with(
                'employments',
                'currentEmployment',
                'futureEmployment',
                'suspensions',
                'currentSuspension',
                'injuries',
                'currentInjury',
                'retirements',
                'currentRetirement'
            );

        return $query;
    }
}
