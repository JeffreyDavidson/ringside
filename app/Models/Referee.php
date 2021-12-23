<?php

namespace App\Models;

use App\Enums\RefereeStatus;
use App\Models\Contracts\IsBookableContract;
use App\Observers\RefereeObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends SingleRosterMember implements IsBookableContract
{
    use Concerns\HasFullName,
        Concerns\IsBookable,
        Concerns\Unguarded,
        HasFactory,
        SoftDeletes;

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::observe(RefereeObserver::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => RefereeStatus::class,
    ];
}
