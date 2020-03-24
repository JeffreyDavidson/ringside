<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class CreateTitleFailureConditionsTest extends TestCase
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
            'introduced_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_creating_a_title()
    {
        $this->actAs(Role::BASIC);

        $response = $this->createRequest('titles');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_title()
    {
        $this->actAs(Role::BASIC);

        $response = $this->storeRequest('titles', $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_a_title()
    {
        $response = $this->createRequest('titles');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_create_a_title()
    {
        $response = $this->storeRequest('title', $this->validParams());

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_title_name_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['name' => '']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function a_title_name_must_contain_at_least_three_characters()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['name' => 'ab']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function a_title_name_must_end_with_title_or_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['name' => 'Example Name']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function a_title_name_must_be_unique()
    {
        $this->actAs(Role::ADMINISTRATOR);
        TitleFactory::new()->create(['name' => 'Example Title']);

        $response = $this->storeRequest('titles', $this->validParams(['name' => 'Example Title']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Title::count());
    }

    /** @test */
    public function a_title_introduced_at_date_is_required()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['introduced_at' => '']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function a_title_introduced_at_must_be_in_datetime_format()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['introduced_at' => now()->toDateString()]));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }

    /** @test */
    public function a_title_introduced_at_must_be_a_datetime_format()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('titles', $this->validParams(['introduced_at' => 'not-a-datetime']));

        $response->assertStatus(302);
        $response->assertRedirect(route('titles.create'));
        $response->assertSessionHasErrors('introduced_at');
        $this->assertEquals(0, Title::count());
    }
}
