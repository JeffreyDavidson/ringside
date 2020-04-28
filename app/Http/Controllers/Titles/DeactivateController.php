<?php

namespace App\Http\Controllers\Titles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Titles\DeactivateRequest;
use App\Models\Title;

class DeactivateController extends Controller
{
    /**
     * Deactivate a title.
     *
     * @param  App\Models\Title  $title
     * @param  App\Http\Requests\Titles\DeactivateRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Title $title, DeactivateRequest $request)
    {
        $title->deactivate();

        return redirect()->route('titles.index');
    }
}
