<?php

namespace App\DataTables\Referees;

use App\Models\Referee;
use Yajra\DataTables\Services\DataTable;

class ReleasedRefereesDataTable extends DataTable
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
            ->editColumn('name', function (Referee $referee) {
                return $referee->full_name;
            })
            ->editColumn('released_at', function (Referee $referee) {
                return $referee->released_at->toDateString();
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
                        'actions' => collect(['employ']),
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
        $query = Referee::released()->withReleasedAtDate();

        return $query;
    }
}
