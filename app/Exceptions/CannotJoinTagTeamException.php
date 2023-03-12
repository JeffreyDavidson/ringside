<?php

namespace App\Exceptions;

use Exception;

class CannotJoinTagTeamException extends Exception
{
    public static function alreadyInTagTeam(): self
    {
        return new static('One or both of the previous wrestlers for this tag team are members of a current tag team so this tag team cannot be restored.');
    }
}
