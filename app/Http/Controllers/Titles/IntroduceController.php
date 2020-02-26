<?php

namespace App\Http\Controllers\Titles;

use App\Exceptions\CannotBeIntroducedException;
use App\Models\Title;
use App\Http\Controllers\Controller;

class IntroduceController extends Controller
{
    /**
     * Introduce a title.
     *
     * @param  \App\Models\Title  $title
     * @return \lluminate\Http\RedirectResponse
     */
    public function __invoke(Title $title)
    {
        $this->authorize('introduce', $title);

        if (! $title->canBeIntroduced()) {
            throw new CannotBeIntroducedException();
        }

        $title->introduce();

        return redirect()->route('titles.index');
    }
}
