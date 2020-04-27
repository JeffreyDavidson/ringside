<?php

namespace App\Providers;

use App\Http\Requests\CustomDataTablesRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('datatables.request', function () {
            return new CustomDataTablesRequest;
        });

        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::replacer('ends_with', function ($message, $attribute, $rule, $parameters) {

            $values = array_pop($parameters);

            if (count($parameters)) {
                $values = implode(', ', $parameters) . ' or ' . $values;
            }

            return str_replace(':values', $values, $message);
        });
    }
}
