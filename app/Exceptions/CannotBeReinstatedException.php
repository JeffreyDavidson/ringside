<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CannotBeReinstatedException extends Exception
{
    /**
     * The default message for sending with exception.
     *
     * @var string
     */
    protected $message = 'This entity cannot be reinstated. This entity is currently employed.';
}
