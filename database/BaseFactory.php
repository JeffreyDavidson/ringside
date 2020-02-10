<?php

use Illuminate\Support\Collection;

abstract class BaseFactory
{
    public $attributes = [];
    protected $propertiesToClone = [];
    protected $count = 1;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function new(array $attributes = [])
    {
        return new static($attributes);
    }

    public function count(int $count)
    {
        $clone = clone $this;
        $clone->count = $count;

        return $clone;
    }

    public function make(callable $callback, $attributes = [])
    {
        if ($this->count > 1) {
            $created = new Collection();
            for ($i = 0; $i < $this->count; $i++) {
                $clone = clone $this;
                $clone->count = 1;
                $created->push($clone->create($attributes));
            }

            return $created;
        }

        return call_user_func($callback, $attributes);
    }

    protected function resolveAttributes($attributes = [])
    {
        /* @var \Faker\Generator $faker */
        $faker = resolve(\Faker\Generator::class);

        // Allows overriding attributes on the final `create` call
        if (! empty($attributes)) {
            return array_replace($this->defaultAttributes($faker), $attributes);
        }

        // Allows setting attributes on the `::new()` call
        if (! empty($this->attributes)) {
            return $this->attributes;
        }

        return $this->defaultAttributes($faker);
    }

    protected function defaultAttributes(Faker\Generator $faker)
    {
        return [];
    }

    abstract public function create($attributes = []);

    protected function __clone()
    {
        foreach ($this->propertiesToClone as $propertyName) {
            if (isset($this->{$propertyName})) {
                $this->{$propertyName} = clone $this->{$propertyName};
            }
        }
    }
}
