<?php

namespace Tests\Feature\Generic\Managers;

use App\Models\Manager;
use App\Exceptions\CannotBeRecoveredException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class RecoverManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_manager_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('bookable')->create();

        $response = $this->recoverRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->recoverRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_manager_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->recoverRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->recoverRequest($manager);

        $response->assertForbidden();
    }
}
