<?php

declare(strict_types=1);

namespace App\Traits\Data;

use App\Models\State;
use Livewire\Attributes\Computed;

trait PresentsStatesList
{
    /**
     * Undocumented function
     *
     * @return array{id: int, name: string}
     */
    #[Computed(cache: true, key: 'states-list', seconds: 180)]
    public function getStates(): array
    {
        return State::query()->pluck('name', 'code')->toArray();
    }
}
