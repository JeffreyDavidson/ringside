<?php

namespace App\Http\Controllers;

use App\Manager;
use App\Http\Requests\StoreManagerRequest;

class ManagersController extends Controller
{
    /**
     * Show the form for creating a manager.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Manager::class);

        return view('managers.create');
    }

    /**
     * Create a new manager.
     *
     * @param  \App\Http\Requests\StoreManagerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreManagerRequest $request)
    {
        Manager::create($request->all());

        return redirect()->route('managers.index');
    }
}
