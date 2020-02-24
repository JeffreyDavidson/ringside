<?php

namespace App\Http\Controllers\Titles;

use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class RetireController extends Controller
{
    /**
     * Retire a title.
     *
     * @param  \App\Models\Title  $title
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Title $title)
    {
        $this->authorize('retire', $title);

        if (! $title->canBeRetired()) {
            throw new CannotBeRetiredException();
        }

        $title->retire();

        return redirect()->route('titles.index');
    }
}
