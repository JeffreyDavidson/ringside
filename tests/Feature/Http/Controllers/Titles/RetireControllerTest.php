<?php

namespace Tests\Feature\Http\Controllers\Titles;

use App\Enums\Role;
use App\Enums\TitleStatus;
use App\Exceptions\CannotBeRetiredException;
use App\Http\Controllers\Titles\RetireController;
use App\Http\Controllers\Titles\TitlesController;
use App\Http\Requests\Titles\RetireRequest;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 * @group feature-titles
 */
class RetireControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_retires_an_active_title_and_redirects($administrators)
    {
        $title = Title::factory()->active()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RetireController::class], $title))
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($title->fresh(), function ($title) {
            $this->assertCount(1, $title->retirements);
            $this->assertEquals(TitleStatus::RETIRED, $title->status);
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_retires_an_inactive_title_and_redirects($administrators)
    {
        $title = Title::factory()->inactive()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RetireController::class], $title))
            ->assertRedirect(action([TitlesController::class, 'index']));

        tap($title->fresh(), function ($title) {
            $this->assertCount(1, $title->retirements);
            $this->assertEquals(TitleStatus::RETIRED, $title->status);
        });
    }

    /**
     * @test
     */
    public function invoke_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(RetireController::class, '__invoke', RetireRequest::class);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_retire_a_title()
    {
        $title = Title::factory()->create();

        $this
            ->actAs(Role::BASIC)
            ->patch(action([RetireController::class], $title))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_retire_a_title()
    {
        $title = Title::factory()->create();

        $this
            ->patch(action([RetireController::class], $title))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_a_retired_title_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $title = Title::factory()->retired()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RetireController::class], $title));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_a_future_activated_title_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $title = Title::factory()->withFutureActivation()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RetireController::class], $title));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function retiring_an_unactivated_title_throws_an_exception($administrators)
    {
        $this->expectException(CannotBeRetiredException::class);
        $this->withoutExceptionHandling();

        $title = Title::factory()->unactivated()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RetireController::class], $title));
    }
}
