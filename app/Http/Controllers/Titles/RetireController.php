<?php

namespace App\Http\Controllers\Titles;

use App\Actions\Titles\RetireAction;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class RetireController extends Controller
{
    /**
     * Retires a title.
     *
     * @param  \App\Models\Title $title
     * @param  \App\Actions\Titles\RetireAction  $action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Title $title, RetireAction $action)
    {
        $this->authorize('retire', $title);

        throw_unless($title->canBeRetired(), new CannotBeRetiredException);

        $action->handle($title);

        return redirect()->route('titles.index');
    }
}
