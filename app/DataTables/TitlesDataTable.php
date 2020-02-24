<?php

namespace App\DataTables;

use App\Models\Title;
use App\Filters\TitleFilters;
use Yajra\DataTables\Services\DataTable;

class TitlesDataTable extends DataTable
{
    /** @var $titleFilters */
    private $titleFilters;

    /**
     * TitleDataTable constructor.
     *
     * @param TitleFilters $titleFilters
     */
    public function __construct(TitleFilters $titleFilters)
    {
        $this->titleFilters = $titleFilters;
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
            ->editColumn('name', function (Title $title) {
                return $title->full_name;
            })
            ->editColumn('introduced_at', function (Title $title) {
                return $title->introduced_at->toDateString();
            })
            ->editColumn('status', function (Title $title) {
                return $title->status->label();
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $sql = "CONCAT(titles.first_name, ' ', titles.last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('action', 'titles.partials.action-cell');
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Title::query();

        $this->titleFilters->apply($query);

        return $query;
    }
}
