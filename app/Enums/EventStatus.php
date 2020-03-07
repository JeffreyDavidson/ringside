<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static EventStatus PENDING()
 * @method static EventStatus SCHEDULED()
 * @method static EventStatus PAST()
 */
final class EventStatus extends Enum
{
    const __default = self::PENDING;

    const PENDING = 'pending';
    const SCHEDULED = 'scheduled';
    const PAST = 'past';
}
