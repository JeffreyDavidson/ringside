<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static StableStatus ACTIVE()
 * @method static StableStatus INACTIVE()
 * @method static StableStatus PENDING_ACTIVATION()
 * @method static StableStatus RETIRED()
 */
final class StableStatus extends Enum
{
    const __default = self::PENDING_ACTIVATION;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const PENDING_ACTIVATION = 'pending-activation';
    const RETIRED = 'retired';
}
