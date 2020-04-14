<?php

namespace App\DataTables\Wrestlers;

use App\Models\Wrestler;
use Yajra\DataTables\Services\DataTable;

class RetiredWrestlersDataTable extends DataTable
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
            ->editColumn('retired_at', function (Wrestler $wrestler) {
                return $wrestler->currentRetirement->started_at->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($wrestler) {
                return view(
                    'wrestlers.partials.action-cell',
                    [
                        'actions' => collect(['unretire']),
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
        $query = Wrestler::retired()->with('currentRetirement');

        return $query;
    }
}
