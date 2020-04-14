<?php

namespace App\DataTables\Titles;

use App\Filters\TitleFilters;
use App\Models\Title;
use Yajra\DataTables\Services\DataTable;

class IntroducedTitlesDataTable extends DataTable
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
            ->filterColumn('id', function ($query, $keyword) {
                $query->where($query->qualifyColumn('id'), $keyword);
            })
            ->addColumn('action', function ($title) {
                return view(
                    'titles.partials.action-cell',
                    [
                        'actions' => collect(['retire', 'introduce']),
                        'model' => $title
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
        $query = Title::introduced();

        $this->titleFilters->apply($query);

        return $query;
    }
}
