<?php

namespace App\Enums;

use MadWeb\Enum\Enum;

/**
 * @method static TitleStatus COMPETABLE()
 * @method static TitleStatus PENDING_INTRODUCTION()
 * @method static TitleStatus RETIRED()
 */
final class TitleStatus extends Enum
{
    const COMPETABLE = 'competable';
    const PENDING_INTRODUCTION = 'pending-introduction';
    const RETIRED = 'retired';
}
