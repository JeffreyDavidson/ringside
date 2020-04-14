<?php

namespace App\DataTables\TagTeams;

use App\Models\TagTeam;
use Yajra\DataTables\Services\DataTable;

class RetiredTagTeamsDataTable extends DataTable
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
            ->editColumn('retired_at', function (TagTeam $tagTeam) {
                return $tagTeam->retired_at->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($tagTeam) {
                return view(
                    'tagteams.partials.action-cell',
                    [
                        'actions' => collect(['unretire']),
                        'model' => $tagTeam
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
        $query = TagTeam::retired()->withRetiredAtDate();

        return $query;
    }
}
