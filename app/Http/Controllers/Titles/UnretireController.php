<?php

declare(strict_types=1);

namespace App\Http\Controllers\Titles;

use App\Actions\Titles\UnretireAction;
use App\Http\Controllers\Controller;
use App\Models\Title;

class UnretireController extends Controller
{
    /**
     * Unretires a title.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Title $title)
    {
        $this->authorize('unretire', $title);

        UnretireAction::run($title);

        return to_route('titles.index');
    }
}
