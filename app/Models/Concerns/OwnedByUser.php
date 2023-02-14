<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

trait OwnedByUser
{
    /**
     * Get the user assigned to the model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
