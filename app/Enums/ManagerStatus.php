<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static ManagerStatus AVAILABLE()
 * @method static ManagerStatus INJURED()
 * @method static ManagerStatus PENDING_EMPLOYMENT()
 * @method static ManagerStatus RELEASED()
 * @method static ManagerStatus RETIRED()
 * @method static ManagerStatus SUSPENDED()
 * @method static ManagerStatus UNEMPLOYED()
 */
final class ManagerStatus extends Enum
{
    const __default = self::UNEMPLOYED;

    const AVAILABLE = 'available';
    const INJURED = 'injured';
    const PENDING_EMPLOYMENT = 'pending-employment';
    const RELEASED = 'released';
    const RETIRED = 'retired';
    const SUSPENDED = 'suspended';
    const UNEMPLOYED = 'unemployed';
}
