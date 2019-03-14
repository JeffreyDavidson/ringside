<?php

namespace App\Models;

use App\Traits\Sluggable;
use App\Traits\Activatable;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use Sluggable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['introduced_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->is_active = $model->introduced_at->lte(today());
        });
    }
}
