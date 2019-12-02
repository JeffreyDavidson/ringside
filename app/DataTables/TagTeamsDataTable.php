<?php

namespace App\DataTables;

use App\Models\TagTeam;
use App\Filters\TagTeamFilters;
use Yajra\DataTables\DataTables;

class TagTeamsDataTable extends DataTables
{
    /** @var TagTeamFilters */
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
     * @return DataTableAbstract
     */
    public function dataTable($query): DataTableAbstract
    {
        return datatables($query)
            ->editColumn('started_at', function (TagTeam $tagTeam) {
                return $tagTeam->currentEmployment->started_at->format('Y-m-d H:s');
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', 'tagteams.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        $query = User::with('employment');

        $this->userFilters->apply($query);

        return $query;
    }
}
