<?php

namespace Tests\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasRequests
{
    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function deleteRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->delete(route("{$entityName}.destroy", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function retireRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.retire", $entity));
    }

        /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function showRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->get(route("{$entityName}.show", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function employRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.employ", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function injureRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.injure", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function suspendRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.suspend", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function recoverRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.recover", $entity));
    }

       /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function reinstateRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.reinstate", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function restoreRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.restore", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @return
     */
    public function unretireRequest(Model $entity)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->put(route("{$entityName}.unretire", $entity));
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @param  array  $overrides
     * @return
     */
    public function updateRequest(Model $entity, $overrides)
    {
        $entityName = Str::replaceFirst('_', '-', $entity->getTable());

        return $this->from(route("{$entityName}.edit", $entity))
                    ->patch(route("{$entityName}.update", $entity), $overrides);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @param  array  $overrides
     * @return
     */
    public function storeRequest($entity, $overrides)
    {
        $entityName = Str::plural($entity);

        return $this->from(route("{$entityName}.create"))
                    ->post(route("{$entityName}.store"), $overrides);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $entity
     * @param  array  $overrides
     * @return
     */
    public function createRequest($entity)
    {
        $entityName = Str::plural($entity);

        return $this->get(route("{$entityName}.create"));
    }
}