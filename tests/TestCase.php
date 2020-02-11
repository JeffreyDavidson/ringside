<?php

namespace Tests;

use App\Enums\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Str;
use JMac\Testing\Traits\HttpTestAssertions;
use UserFactory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        HttpTestAssertions,
        Traits\HasRequests;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });
    }

    public function actAs($role = Role::BASIC, $attributes = [])
    {
        $user = UserFactory::new()->withRole($role)->create($attributes);

        $this->actingAs($user);

        return $user;
    }

    /**
     * Assert that the given class uses the provided trait name.
     *
     * @param  string  $trait
     * @param  mixed   $class
     * @return void
     */
    public function assertUsesTrait($trait, $class)
    {
        $this->assertContains($trait, class_uses($class));
    }
}
