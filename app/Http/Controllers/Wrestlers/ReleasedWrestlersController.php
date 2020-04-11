<?php

namespace App\Http\Controllers\Wrestlers;

use App\DataTables\Wrestlers\ReleasedWrestlersDataTable;
use App\Http\Controllers\Controller;

class ReleasedWrestlersController extends Controller
{
    /**
     * View a list of released wrestlers.
     *
     * @param  App\DataTables\Wrestlers\ReleasedWrestlersDataTable  $dataTable
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ReleasedWrestlersDataTable $dataTable)
    {
        return $dataTable->ajax();
    }
}
