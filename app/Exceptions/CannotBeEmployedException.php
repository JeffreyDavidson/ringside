<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeEmployedException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be employed. This entity does have a start date.';
}
