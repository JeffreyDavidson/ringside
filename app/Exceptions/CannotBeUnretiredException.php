<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeUnretiredException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be unretired. This entity has a current employment.';
}
