<?php

namespace App\Http\Controllers;

use App\Wrestler;
use App\Http\Requests\StoreRetirementRequest;

class RetiredWrestlersController extends Controller
{
    /**
     * Create a retirement for the wrestler.
     *
     * @param  \App\Http\Requests\StoreRetirementRequest  $request
     * @param  \App\Wrestler  $wrestler
     * @return void
     */
    public function store(StoreRetirementRequest $request, Wrestler $wrestler)
    {
        $wrestler->retire();

        return redirect(route('retired-wrestlers.index'));
    }
}
