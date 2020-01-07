<?php

namespace App\Http\Controllers\API;

use App\Models\Manager;
use App\Filters\ManagerFilters;
use App\Http\Controllers\Controller;
use App\DataTables\ManagersDataTable;

class ManagersController extends Controller
{
    /**
     * Retrieve filtered list of managers.
     *
     * @param  App\DataTables\ManagersDataTable  $table
     * @param  App\Filters\ManagerFilters  $requestFilter
     * @return \Illuminate\Response\
     */
    public function index(ManagersDataTable $dataTable, ManagerFilters $requestFilter)
    {
        $this->authorize('viewList', Manager::class);

        $model = Manager::query();
        // dd($dataTable->eloquent($model)->toJson());

        // return $dataTable->eloquent($model)->toJson();
        return $dataTable->of(Manager::query())->toJson();
    }
}
