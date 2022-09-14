<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeDeactivatedException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'Entity cannot be deactivated. This entity is not currently activated.';
}
