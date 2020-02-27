<?php

namespace Tests\Feature\Admin\Managers;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class ReinstateManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_reinstate_a_suspended_manager()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($manager);

        $response->assertRedirect(route('managers.index'));
        tap($manager->fresh(), function ($manager) use ($now) {
            $this->assertEquals($now->toDateTimeString(), $manager->suspensions()->latest()->first()->ended_at);
        });
    }
}
