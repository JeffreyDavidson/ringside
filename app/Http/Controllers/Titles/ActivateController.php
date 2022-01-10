<?php

namespace App\Http\Controllers\Titles;

use App\Actions\Titles\ActivateAction;
use App\Exceptions\CannotBeActivatedException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class ActivateController extends Controller
{
    /**
     * Activates a title.
     *
     * @param  \App\Models\Title $title
     * @param  \App\Actions\Titles\ActivateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Title $title, ActivateAction $action)
    {
        $this->authorize('activate', $title);

        throw_unless($title->canBeActivated(), new CannotBeActivatedException);

        $action->handle($title);

        return redirect()->route('titles.index');
    }
}
