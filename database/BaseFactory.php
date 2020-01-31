<?php

class BaseFactory
{
    protected $propertiesToClone = [];

    public function __clone()
    {
        foreach ($this->propertiesToClone as $propertyName) {
            if (isset($this->{$propertyName})) {
                $this->{$propertyName} = clone $this->{$propertyName};
            }
        }
    }
}
