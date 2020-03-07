<?php

namespace App\Filters\Concerns;

use Illuminate\Support\Str;

trait FiltersByStatus
{
    /**
     * Filter a query to include models of a status.
     *
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        $model = $this->builder->getModel();

        // if (method_exists($model, 'scope' . Str::studly($status))) {
        //     $this->builder->{Str::camel($status)}();
        // } else {
        //     throw new InvalidArgumentException('This scope does not exist on the '.$model.' class.');
        // }

        try {
            method_exists($model, 'scope'.Str::studly($status));
        } catch (\Exception $e) {
            info('This scope does not exist on the '.$model.' class.');
        }

        // return $this->builder;
        return $this->builder->{Str::camel($status)}();
    }
}
