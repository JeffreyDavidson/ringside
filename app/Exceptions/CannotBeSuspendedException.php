<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeSuspendedException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be suspended. This entity is currently suspended.';
}
