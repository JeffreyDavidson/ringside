<?php

namespace App\Http\Controllers\Wrestlers;

use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wrestlers\InjureRequest;
use App\Models\Wrestler;
use App\Repositories\WrestlerRepository;

class InjureController extends Controller
{
    /**
     * Injure a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \App\Http\Requests\Wrestlers\InjureRequest  $request
     * @param  \App\Repositories\WrestlerRepository $wrestlerRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Wrestler $wrestler, InjureRequest $request, WrestlerRepository $wrestlerRepository)
    {
        throw_unless($wrestler->canBeInjured(), new CannotBeInjuredException);

        $injureDate = now()->toDateTimeString();

        $wrestlerRepository->injure($wrestler, $injureDate);
        $wrestler->updateStatusAndSave();
        $wrestler->currentTagTeam?->updateStatusAndSave();

        return redirect()->route('wrestlers.index');
    }
}
