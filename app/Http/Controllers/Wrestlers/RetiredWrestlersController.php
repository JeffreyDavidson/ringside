<?php

namespace App\Http\Controllers\Wrestlers;

use App\DataTables\Wrestlers\RetiredWrestlersDataTable;
use App\Http\Controllers\Controller;

class RetiredWrestlersController extends Controller
{
    /**
     * View a list of retired wrestlers.
     *
     * @param  App\DataTables\Wrestlers\RetiredWrestlersDataTable  $dataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RetiredWrestlersDataTable $dataTable)
    {
        return $dataTable->ajax();
    }
}
