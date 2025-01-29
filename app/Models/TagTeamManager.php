<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TagTeamManager extends Pivot
{
    protected $table = 'tag_teams_managers';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hired_at' => 'datetime',
        'left_at' => 'datetime',
    ];
}
