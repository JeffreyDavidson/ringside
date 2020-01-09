<?php

namespace App\Http\Controllers\API;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\DataTables\ManagersDataTable;

class ManagersController extends Controller
{
    /**
     * Retrieve list of managers.
     *
     * @param  App\DataTables\ManagersDataTable  $dataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ManagersDataTable $dataTable)
    {
        $this->authorize('viewList', Manager::class);

        return $dataTable->ajax();
    }
}
