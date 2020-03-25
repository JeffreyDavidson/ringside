<?php

namespace Tests\Feature\Wrestlers;

use App\Enums\Role;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class CreateWrestlerSuccessConditionsTest extends TestCase
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
            'name' => 'Example Wrestler Name',
            'feet' => '6',
            'inches' => '4',
            'weight' => '240',
            'hometown' => 'Laraville, FL',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_a_wrestler()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->createRequest('wrestler');

        $response->assertViewIs('wrestlers.create');
        $response->assertViewHas('wrestler', new Wrestler);
    }

    /** @test */
    public function an_administrator_can_create_a_wrestler()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('wrestler', $this->validParams());

        $response->assertRedirect(route('wrestlers.index'));
    }

    /** @test */
    public function a_wrestler_signature_move_is_optional()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('wrestler', $this->validParams(['signature_move' => null]));

        $response->assertSessionDoesntHaveErrors('signature_move');
    }

    /** @test */
    public function a_wrestler_started_at_date_is_optional()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('wrestler', $this->validParams(['started_at' => null]));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_wrestler_can_be_created()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('wrestler', $this->validParams());

        $response->assertRedirect(route('wrestlers.index'));
        tap(Wrestler::first(), function ($wrestler) {
            $this->assertEquals('Example Wrestler Name', $wrestler->name);
            $this->assertEquals(76, $wrestler->height);
            $this->assertEquals(240, $wrestler->weight);
            $this->assertEquals('Laraville, FL', $wrestler->hometown);
            $this->assertEquals('The Finisher', $wrestler->signature_move);
        });
    }

    /** @test */
    public function a_wrestler_can_be_employed_during_creation()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('wrestler', $this->validParams(['started_at' => $now->toDateTimeString()]));

        tap(Wrestler::first(), function ($wrestler) {
            $this->assertTrue($wrestler->isCurrentlyEmployed());
        });
    }

    /** @test */
    public function a_wrestler_can_be_created_without_employing()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('wrestler', $this->validParams(['started_at' => null]));

        tap(Wrestler::first(), function ($wrestler) {
            $this->assertFalse($wrestler->isCurrentlyEmployed());
        });
    }
}
