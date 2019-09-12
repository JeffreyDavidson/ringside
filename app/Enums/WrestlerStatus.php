<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static WrestlerStatus BOOKABLE()
 * @method static WrestlerStatus PENDING_EMPLOYMENT()
 * @method static WrestlerStatus INJURED()
 * @method static WrestlerStatus SUSPENDED()
 * @method static WrestlerStatus RETIRED()
 */
final class WrestlerStatus extends Enum
{
    const BOOKABLE = 'bookable';
    const PENDING_EMPLOYMENT = 'pending-employment';
    const INJURED = 'injured';
    const SUSPENDED = 'suspended';
    const RETIRED = 'retired';
}
