<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static RefereeStatus BOOKABLE()
 * @method static RefereeStatus PENDING_EMPLOYMENT()
 * @method static RefereeStatus INJURED()
 * @method static RefereeStatus SUSPENDED()
 * @method static RefereeStatus RETIRED()
 */
final class RefereeStatus extends Enum
{
    const __default = self::PENDING_EMPLOYMENT;

    const BOOKABLE = 'bookable';
    const PENDING_EMPLOYMENT = 'pending-employment';
    const INJURED = 'injured';
    const SUSPENDED = 'suspended';
    const RETIRED = 'retired';
}
