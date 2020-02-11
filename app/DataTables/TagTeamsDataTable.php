<?php

namespace App\DataTables;

use App\Filters\TagTeamFilters;
use App\Models\TagTeam;
use Yajra\DataTables\Services\DataTable;

class TagTeamsDataTable extends DataTable
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
            ->addColumn('action', 'tagteams.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = TagTeam::whereHas('employments');

        $this->tagTeamFilters->apply($query);

        return $query;
    }
}
