<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static StableStatus ACTIVE()
 * @method static StableStatus PENDING_INTRODUCTION()
 * @method static StableStatus RETIRED()
 */
final class StableStatus extends Enum
{
    const ACTIVE = 'active';
    const PENDING_INTRODUCTION = 'pending-introduction';
    const RETIRED = 'retired';
}
