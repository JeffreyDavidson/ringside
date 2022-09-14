<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeActivatedException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be activated. This entity is currently active.';
}
