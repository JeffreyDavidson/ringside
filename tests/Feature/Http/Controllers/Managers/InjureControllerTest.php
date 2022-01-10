<?php

namespace Tests\Feature\Http\Controllers\Managers;

use App\Enums\ManagerStatus;
use App\Enums\Role;
use App\Exceptions\CannotBeInjuredException;
use App\Http\Controllers\Managers\InjureController;
use App\Http\Controllers\Managers\ManagersController;
use App\Models\Manager;
use Tests\TestCase;

/**
 * @group managers
 * @group feature-managers
 * @group roster
 * @group feature-roster
 */
class InjureControllerTest extends TestCase
{
    /**
     * @test
     */
    public function invoke_injures_an_available_manager_and_redirects()
    {
        $this->withoutExceptionHandling();
        $manager = Manager::factory()->available()->create();

        $this
            ->actAs(Role::administrator())
            ->patch(action([InjureController::class], $manager))
            ->assertRedirect(action([ManagersController::class, 'index']));

        tap($manager->fresh(), function ($manager) {
            $this->assertCount(1, $manager->injuries);
            $this->assertEquals(ManagerStatus::injured(), $manager->status);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_injure_a_manager()
    {
        $manager = Manager::factory()->withFutureEmployment()->create();

        $this
            ->actAs(Role::basic())
            ->patch(route('managers.injure', $manager))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_injure_a_manager()
    {
        $manager = Manager::factory()->create();

        $this
            ->patch(route('managers.injure', $manager))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider noninjurablemanagerTypes
     *
     * @param mixed $factoryState
     */
    public function invoke_throws_exception_for_injuring_a_non_injurable_manager($factoryState)
    {
        $this->expectException(CannotBeInjuredException::class);
        $this->withoutExceptionHandling();

        $manager = Manager::factory()->{$factoryState}()->create();

        $this
            ->actAs(Role::administrator())
            ->patch(route('managers.injure', $manager));
    }

    public function noninjurablemanagerTypes()
    {
        return [
            'unemployed manager' => ['unemployed'],
            'suspended manager' => ['suspended'],
            'released manager' => ['released'],
            'with future employed manager' => ['withFutureEmployment'],
            'retired manager' => ['retired'],
            'injured manager' => ['injured'],
        ];
    }
}
