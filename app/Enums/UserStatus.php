<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case Unverified = 'unverified';
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Unverified => 'Unverified',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }
}
