<?php

namespace Tests\Feature\Generic\Referees;

use App\Models\Referee;
use App\Exceptions\CannotBeRecoveredException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class RecoverRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_referee_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('bookable')->create();

        $response = $this->recoverRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('pending-employment')->create();

        $response = $this->recoverRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_referee_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('retired')->create();

        $response = $this->recoverRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_recovered()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRecoveredException::class);

        $this->actAs('administrator');
        $referee = factory(Referee::class)->states('suspended')->create();

        $response = $this->recoverRequest($referee);

        $response->assertForbidden();
    }
}
