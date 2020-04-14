<?php

namespace App\DataTables\TagTeams;

use App\Filters\TagTeamFilters;
use App\Models\TagTeam;
use Yajra\DataTables\Services\DataTable;

class EmployedTagTeamsDataTable extends DataTable
{
    /** @var $tagTeamFilters */
    private $tagTeamFilters;

    /**
     * TagTeamDataTable constructor.
     *
     * @param TagTeamFilters $tagTeamFilters
     */
    public function __construct(TagTeamFilters $tagTeamFilters)
    {
        $this->tagTeamFilters = $tagTeamFilters;
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
            ->editColumn('started_at', function (TagTeam $tagTeam) {
                return $tagTeam->started_at->toDateString();
            })
            ->editColumn('status', function (TagTeam $tagTeam) {
                return $tagTeam->status->label();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($tagTeam) {
                return view(
                    'tagteams.partials.action-cell',
                    [
                        'actions' => collect(['retire', 'employ', 'release', 'suspend', 'reinstate']),
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
        $query = TagTeam::employed()->withEmployedAtDate();

        $this->tagTeamFilters->apply($query);

        return $query;
    }
}
