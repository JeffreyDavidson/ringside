<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static TitleStatus ACTIVE()
 * @method static TitleStatus INACTIVE()
 * @method static TitleStatus PENDING_ACTIVATION()
 * @method static TitleStatus RETIRED()
 */
final class TitleStatus extends Enum
{
    const __default = self::PENDING_ACTIVATION;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const PENDING_ACTIVATION = 'pending-activation';
    const RETIRED = 'retired';
}
