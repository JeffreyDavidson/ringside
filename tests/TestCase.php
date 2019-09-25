<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
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

    public function actAs($states = [], $attributes = [])
    {
        $user = ($states instanceof User) ? $states : factory(User::class)->states($states)->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    protected function ajaxJson($url)
    {
        return $this->getJson($url, ['X-Requested-With' => 'XMLHttpRequest']);
    }

    /**
     * Assert that the given class soft deletes.
     *
     * @param  string  $model
     * @return void
     */
    public function assertSoftDeletes(string $model)
    {
        $instance = new $model;

        $this->assertUsesTrait(\Illuminate\Database\Eloquent\SoftDeletes::class, $instance);
        $this->assertContains('deleted_at', $instance->getDates());
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
