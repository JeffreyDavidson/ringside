<?php

namespace App\Http\Controllers\Titles;

use App\Exceptions\CannotBeUnretiredException;
use App\Http\Controllers\Controller;
use App\Models\Title;

class UnretireController extends Controller
{
    /**
     * Unretire a title.
     *
     * @param  \App\Models\Title  $title
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Title $title)
    {
        $this->authorize('unretire', $title);

        if (! $title->isRetired()) {
            throw new CannotBeUnretiredException();
        }

        $title->unretire();

        return redirect()->route('titles.index');
    }
}
