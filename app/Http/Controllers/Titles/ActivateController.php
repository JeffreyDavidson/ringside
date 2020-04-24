<?php

namespace App\Http\Controllers\Titles;

use App\Exceptions\CannotBeActivatedException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class ActivateController extends Controller
{
    /**
     * Activate a title.
     *
     * @param  App\Models\Title  $title
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Title $title)
    {
        $this->authorize('activate', $title);

        if (! $title->canBeActivated()) {
            throw new CannotBeActivatedException();
        }

        $title->activate();

        return redirect()->route('titles.index');
    }
}
