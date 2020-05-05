<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class UpdateTitleFailureConditionsTest extends TestCase
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
            'name' => 'Example Name Title',
            'activated_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_editing_a_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->create();

        $response = $this->editRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_a_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->create();

        $response = $this->updateRequest($title, $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_a_title()
    {
        $title = TitleFactory::new()->create();

        $response = $this->editRequest($title);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_update_a_title()
    {
        $title = TitleFactory::new()->create();

        $response = $this->updateRequest($title, $this->validParams());

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_title_name_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Old Title Name']);

        $response = $this->updateRequest($title, $this->validParams(['name' => '']));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Title Name', $title->name);
        });
    }

    /** @test */
    public function a_title_name_must_contain_at_least_3_characters()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Old Title Name']);

        $response = $this->updateRequest($title, $this->validParams(['name' => 'ab']));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Title Name', $title->name);
        });
    }

    /** @test */
    public function a_title_name_must_end_with_title_or_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Old Title Name']);

        $response = $this->updateRequest($title, $this->validParams(['name' => 'Example Name']));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Old Title Name', $title->name);
        });
    }

    /** @test */
    public function a_title_name_must_be_unique()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Example One Title']);
        TitleFactory::new()->create(['name' => 'Example Two Title']);

        $response = $this->updateRequest($title, $this->validParams(['name' => 'Example Two Title']));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('name');
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Example One Title', $title->name);
        });
    }

    /** @test */
    public function a_title_activated_at_must_be_in_datetime_format()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->updateRequest($title, $this->validParams(['activated_at' => $now->toDateString()]));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('activated_at');
        tap($title->fresh(), function ($title) use ($now) {
            dd($title->activated_at);
            $this->assertEquals($now->toDateTimeString(), $title->activated_at->toDateTimeString());
        });
    }

    /** @test */
    public function a_title_activated_at_must_be_a_datetime_format()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->updateRequest($title, $this->validParams(['activated_at' => 'not-a-datetime']));

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('activated_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals(today()->toDateString(), $title->activated_at->toDateString());
        });
    }

    /** @test */
    public function a_title_that_has_been_activated_in_the_past_must_be_activated_before_or_on_same_day()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['activated_at' => Carbon::yesterday()->toDateTimeString()]);

        $response = $this->updateRequest(
            $title,
            $this->validParams(['activated_at' => now()->addDays(3)->toDateTimeString()])
        );

        $response->assertRedirect(route('titles.edit', $title));
        $response->assertSessionHasErrors('activated_at');
        tap($title->fresh(), function ($title) {
            $this->assertEquals(Carbon::yesterday()->toDateTimeString(), $title->activated_at->toDateTimeString());
        });
    }
}
