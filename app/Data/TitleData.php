<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Carbon;

readonly class TitleData
{
    public function __construct(
        public string $name,
        public ?Carbon $activation_date
    ) {}

    public static function fromForm($data): self
    {
        return new self(
            $data['name'],
            $data['activation_date'],
        );
    }
}
