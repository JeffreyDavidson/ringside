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
                return $wrestler->previousEmployment->ended_at->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($wrestler) {
                return view(
                    'wrestlers.partials.action-cell',
                    [
                        'actions' => collect(['employ']),
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
        $query = Wrestler::released()->with('previousEmployment');

        return $query;
    }
}
