<?php

namespace App\Http\Controllers\Referees;

use App\Models\Referee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ViewModels\RefereeViewModel;
use App\DataTables\RefereesDataTable;
use App\Http\Requests\Referees\StoreRequest;
use App\Http\Requests\Referees\UpdateRequest;

class RefereesController extends Controller
{
    /**
     * View a list of wrestlers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\DataTables\RefereesDataTable  $dataTable
     * @return \Illuminate\View\View|Illuminate\Http\JsonResponse
     */
    public function index(Request $request, RefereesDataTable $dataTable)
    {
        $this->authorize('viewList', Referee::class);

        if ($request->ajax()) {
            return $dataTable->ajax();
        }

        return view('referees.index');
    }

    /**
     * Show the form for creating a new referee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Referee::class);

        return view('referees.create', new RefereeViewModel());
    }

    /**
     * Create a new referee.
     *
     * @param  App\Http\Requests\Referees\StoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        $referee = Referee::create($request->except('started_at'));

        if ($request->filled('started_at')) {
            $referee->employ($request->input('started_at'));
        }

        return redirect()->route('referees.index');
    }

    /**
     * Show the profile of a referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \Illuminate\View\View
     */
    public function show(Referee $referee)
    {
        $this->authorize('view', $referee);

        return view('referees.show', compact('referee'));
    }

    /**
     * Show the form for editing a referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \Illuminate\Http\Response
     */
    public function edit(Referee $referee)
    {
        $this->authorize('update', $referee);

        return view('referees.edit', new RefereeViewModel($referee));
    }

    /**
     * Update a given referee.
     *
     * @param  App\Http\Requests\Referees\UpdateRequest  $request
     * @param  App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Referee $referee)
    {
        $referee->update($request->except('started_at'));

        $referee->employ($request->input('started_at'));

        return redirect()->route('referees.index');
    }

    /**
     * Delete a referee.
     *
     * @param  App\Models\Referee  $referee
     * @return \lluminate\Http\RedirectResponse
     */
    public function destroy(Referee $referee)
    {
        $this->authorize('delete', $referee);

        $referee->delete();

        return redirect()->route('referees.index');
    }
}
