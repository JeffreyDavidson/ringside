<?php

namespace App\Http\Controllers\Managers;

use App\Models\Manager;
use App\Http\Controllers\Controller;
use App\Exceptions\CannotBeEmployedException;
use App\Http\Requests\Managers\EmployRequest;

class EmployController extends Controller
{
    /**
     * Employ a manager.
     *
     * @param  App\Models\Manager  $manager
     * @param  App\Http\Requests\Managers\EmployRequest  $request
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Manager $manager, EmployRequest $request)
    {
        $manager->employ($request->input('started_at'));

        return redirect()->route('managers.index');
    }
}
