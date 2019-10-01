<?php

namespace Tests\Feature\Admin\Manager;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class CreateManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_a_manager()
    {
        $this->actAs('administrator');

        $response = $this->createRequest('manager');

        $response->assertViewIs('managers.create');
        $response->assertViewHas('manager', new Manager);
    }

    /** @test */
    public function an_administrator_can_create_a_manager()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');

        $response = $this->storeRequest('manager', $this->validParams());

        $response->assertRedirect(route('managers.index'));
        tap(Manager::first(), function ($manager) use ($now) {
            $this->assertEquals('John', $manager->first_name);
            $this->assertEquals('Smith', $manager->last_name);
            $this->assertEquals($now->toDateTimeString(), $manager->currentEmployment->started_at);
        });
    }
}
