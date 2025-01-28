<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Carbon;

class WrestlerData
{
    /**
     * Create a new wrestler data instance.
     */
    public function __construct(
        public string $name,
        public int $height,
        public int $weight,
        public string $hometown,
        public ?string $signature_move,
        public ?Carbon $start_date,
    ) {}

    public static function fromForm($data): self
    {
        return new self(
            $data['name'],
            $data['height'],
            $data['weight'],
            $data['hometown'],
            $data['signature_move'],
            $data['start_date'],
        );
    }
}
