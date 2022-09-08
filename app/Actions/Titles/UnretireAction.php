<?php

declare(strict_types=1);

namespace App\Actions\Titles;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Title;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UnretireAction extends BaseTitleAction
{
    use AsAction;

    /**
     * Unretire a title.
     *
     * @param  \App\Models\Title  $title
     * @param  \Illuminate\Support\Carbon|null  $unretiredDate
     * @return void
     */
    public function handle(Title $title, ?Carbon $unretiredDate = null): void
    {
        throw_unless($title->canBeUnretired(), CannotBeUnretiredException::class);

        $unretiredDate ??= now();

        $this->titleRepository->unretire($title, $unretiredDate);
        $this->titleRepository->activate($title, $unretiredDate);
    }
}
