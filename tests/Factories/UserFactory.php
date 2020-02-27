<?php

namespace Tests\Factories;

use App\Enums\Role;
use App\Models\User;
use Faker\Generator;
use Illuminate\Support\Str;

class UserFactory extends BaseFactory
{
    protected $factoriesToClone = [];
    protected $role;

    public function administrator()
    {
        return $this->withClone(fn ($factory) => $factory->role = Role::ADMINISTRATOR);
    }

    public function basic()
    {
        return $this->withClone(fn ($factory) => $factory->role = Role::BASIC);
    }

    public function superAdministrator()
    {
        return $this->withClone(fn ($factory) => $factory->role = Role::SUPER_ADMINISTRATOR);
    }

    public function create($attributes = [])
    {
        return $this->make(function ($attributes) {
            $user = User::create($this->resolveAttributes($attributes));

            return $user;
        }, $attributes);
    }

    public function withRole($role)
    {
        return $this->withClone(fn ($factory) => $factory->role = $role);
    }

    protected function defaultAttributes(Generator $faker)
    {
        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => Str::random(10),
            'role' => $this->role ?? Role::__default,
        ];
    }
}
