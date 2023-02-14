<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Query\Builder;
use App\Builders\RefereeQueryBuilder;
use App\Enums\RefereeStatus;
use App\Models\Contracts\Bookable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends SingleRosterMember implements Bookable
{
    use Concerns\HasFullName;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => RefereeStatus::class,
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @return \App\Builders\RefereeQueryBuilder<Referee>
     */
    public function newEloquentBuilder(Builder $query): RefereeQueryBuilder
    {
        return new RefereeQueryBuilder($query);
    }
}
