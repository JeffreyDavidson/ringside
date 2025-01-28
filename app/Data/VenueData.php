<?php

declare(strict_types=1);

namespace App\Data;

readonly class VenueData
{
    /**
     * Create a new venue data instance.
     */
    public function __construct(
        public string $name,
        public string $street_address,
        public string $city,
        public string $state,
        public string $zipcode,
    ) {}

    public static function fromForm($data): self
    {
        return new self(
            $data['name'],
            $data['street_address'],
            $data['city'],
            $data['state'],
            $data['zipcode'],
        );
    }
}
