<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeClearedFromInjuryException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity could not be cleared from an injury.';
}
