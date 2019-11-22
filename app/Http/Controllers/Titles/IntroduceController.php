<?php

namespace App\Http\Controllers\Titles;

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

        $title->introduce();

        return redirect()->route('titles.index');
    }
}
