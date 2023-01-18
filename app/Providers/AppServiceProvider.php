<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('orderByNullsLast', function ($column, $direction = 'asc') {
            /** @var Builder $builder */
            $builder = $this;
            $column = $builder->getGrammar()->wrap($column);
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

            return $builder->orderByRaw("{$column} IS NULL {$direction}, {$column} {$direction}");
        });

        Relation::enforceMorphMap([
            'wrestler' => 'App\Models\Wrestler',
            'manager' => 'App\Models\Manager',
            'title' => 'App\Models\Title',
            'tagteam' => 'App\Models\TagTeam',
        ]);
    }
}
