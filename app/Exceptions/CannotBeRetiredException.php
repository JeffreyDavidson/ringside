<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeRetiredException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be retired. This entity does not have an active employment';
}
