<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 * @group generics
 */
class CreateTitleSuccessConditionsTest extends TestCase
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
    public function a_title_introduced_today_or_before_is_competable()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('titles', $this->validParams(['introduced_at' => today()->toDateTimeString()]));

        tap(Title::first(), function ($title) {
            $this->assertTrue($title->is_competable);
        });
    }

    /** @test */
    public function a_title_introduced_after_today_is_pending_introduction()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('titles', $this->validParams(['introduced_at' => Carbon::tomorrow()->toDateTimeString()]));

        tap(Title::first(), function ($title) {
            $this->assertTrue($title->is_pending_introduction);
        });
    }
}
