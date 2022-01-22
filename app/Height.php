<?php

namespace App;

class Height
{
    /**
     * The feet measurement of the roster member.
     *
     * @var string
     */
    public $feet;

    /**
     * The feet measurement of the roster member.
     *
     * @var string
     */
    public $inches;

    /**
     * Create a new height instance.
     *
     * @param string
     * @param string $height
     */
    public function __construct(string $height)
    {
        $this->feet = (int) ($height / 12);
        $this->inches = $height % 12;
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
     * Return the wrestler's height in inches.
     *
     * @return string
     */
    public function inInches()
    {
        return $this->height;
    }
}
