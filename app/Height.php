<?php

namespace App;

class Height
{
    /**
     * @var string
     */
    public $height;

    /**
     * @param string
     * @param string $height
     */
    public function __construct(string $height)
    {
        $this->height = $height;
    }

    /**
     * Return the wrestler's height formatted.
     *
     * @return string
     */
    public function getFormattedHeightAttribute()
    {
        return "{$this->feet}'{$this->inches}\"";
    }

    /**
     * Return the wrestler's height in feet.
     *
     * @return string
     */
    public function feet()
    {
        return (int) ($this->height / 12);
    }

    /**
     * Return the wrestler's height in inches.
     *
     * @return string
     */
    public function inches()
    {
        return $this->height % 12;
    }

    /**
     * Return the wrestler's height in inches.
     *
     * @return string
     */
    public function inInches()
    {
        return $this->height;
    }

    public function __toString()
    {
        return (string) ($this->height);
    }
}
