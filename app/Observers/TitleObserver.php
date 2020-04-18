<?php

namespace App\Observers;

use App\Models\Title;
use App\Enums\TitleStatus;

class TitleObserver
{
    /**
     * Handle the Title "saving" event.
     *
     * @param  App\Models\Title  $title
     * @return void
     */
    public function saving(Title $title)
    {
        // dd($title->isCurrentlyActivated());
        if ($title->isRetired()) {
            $title->status = TitleStatus::RETIRED;
        } elseif ($title->isCurrentlyActivated()) {
            // dd('testing');
            $title->status = TitleStatus::ACTIVE;
        } elseif ($title->isDeactivated()) {
            // dd('deactivated');
            $title->status = TitleStatus::INACTIVE;
        } else {
            // dd('pending activation');
            $title->status = TitleStatus::PENDING_ACTIVATION;
        }
    }
}
