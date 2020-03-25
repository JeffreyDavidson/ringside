<?php

namespace Tests\Factories;

use App\Models\Venue;
use Faker\Generator;

class VenueFactory extends BaseFactory
{
    public $softDeleted = false;

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $event = Venue::create($this->resolveAttributes($attributes));

            if ($this->softDeleted) {
                $event->delete();
            }

            return $event;
        }, $attributes);
    }

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'name' => $faker->sentence,
            'address1' => $faker->buildingNumber. ' '. $faker->streetName,
            'address2' => $faker->optional()->secondaryAddress,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
        ];
    }
}
