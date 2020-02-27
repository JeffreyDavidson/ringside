<?php

namespace Tests\Feature\SuperAdmin\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group superadmins
 */
class DeleteTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_competable_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->deleteRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertSoftDeleted('titles', ['name' => $title->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_introduction_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->deleteRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertSoftDeleted('titles', ['name' => $title->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->deleteRequest($title);

        $response->assertRedirect(route('titles.index'));
        $this->assertSoftDeleted('titles', ['name' => $title->name]);
    }
}
