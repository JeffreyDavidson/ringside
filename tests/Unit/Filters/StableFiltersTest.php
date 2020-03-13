<?php

namespace Tests\Unit\Filters;

use App\Filters\Concerns\FiltersByStatus;
use App\Filters\StableFilters;
use Tests\TestCase;

/**
 * @group stable
 * @group roster
 * @group filters
 */
class StableFiltersTest extends TestCase
{
    /** @var App\Filters\StableFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(StableFilters::class);
    }

    /** @test */
    public function stable_filters_include_filtering_by_status()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }
}
