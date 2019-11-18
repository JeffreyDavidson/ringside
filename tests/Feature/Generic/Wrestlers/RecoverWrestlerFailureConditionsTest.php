<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use App\Exceptions\CannotBeRecoveredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class RecoverWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_wrestler_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $response = $this->recoverRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->recoverRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $response = $this->recoverRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_retired_wrestler_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $response = $this->recoverRequest($wrestler);

        $response->assertForbidden();
    }
}
