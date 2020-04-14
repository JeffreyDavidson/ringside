<?php

namespace App\DataTables\TagTeams;

use App\Models\TagTeam;
use Yajra\DataTables\Services\DataTable;

class ReleasedTagTeamsDataTable extends DataTable
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
            ->editColumn('released_at', function (TagTeam $tagTeam) {
                return $tagTeam->released_at->toDateString();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($tagTeam) {
                return view(
                    'tagteams.partials.action-cell',
                    [
                        'actions' => collect(['employ']),
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
        $query = TagTeam::released()->withReleasedAtDate();

        return $query;
    }
}
