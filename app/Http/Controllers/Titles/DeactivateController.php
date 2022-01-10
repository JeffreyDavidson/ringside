<?php

namespace App\Http\Controllers\Titles;

use App\Actions\Titles\DeactivateAction;
use App\Exceptions\CannotBeDeactivatedException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class DeactivateController extends Controller
{
    /**
     * Deactivates a title.
     *
     * @param  \App\Models\Title  $title
     * @param  \App\Actions\Titles\DeactivateAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Title $title, DeactivateAction $action)
    {
        $this->authorize('deactivate', $title);

        throw_unless($title->canBeDeactivated(), new CannotBeDeactivatedException);

        $action->handle($title);

        return redirect()->route('titles.index');
    }
}
