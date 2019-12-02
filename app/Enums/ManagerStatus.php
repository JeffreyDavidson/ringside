<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static ManagerStatus AVAILABLE()
 * @method static ManagerStatus PENDING_EMPLOYMENT()
 * @method static ManagerStatus INJURED()
 * @method static ManagerStatus SUSPENDED()
 * @method static ManagerStatus RETIRED()
 */
final class ManagerStatus extends Enum
{
    const AVAILABLE = 'available';
    const PENDING_EMPLOYMENT = 'pending-employment';
    const INJURED = 'injured';
    const SUSPENDED = 'suspended';
    const RETIRED = 'retired';
}
