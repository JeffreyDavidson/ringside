<?php

namespace Tests\Feature\Admin\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group admins
 */
class UpdateTitleSuccessConditionsTest extends TestCase
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
    public function an_administrator_can_view_the_form_for_editing_a_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->editRequest($title);

        $response->assertViewIs('titles.edit');
        $this->assertTrue($response->data('title')->is($title));
    }

    /** @test */
    public function an_administrator_can_update_a_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->updateRequest($title, $this->validParams());

        $response->assertRedirect(route('titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertEquals('Example Name Title', $title->name);
        });
    }
}
