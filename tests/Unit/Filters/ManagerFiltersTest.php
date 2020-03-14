<?php

namespace Tests\Unit\Filters;

use App\Filters\Concerns\FiltersByStartDate;
use App\Filters\Concerns\FiltersByStatus;
use App\Filters\ManagerFilters;
use Tests\TestCase;

/**
 * @group managers
 * @group roster
 * @group filters
 */
class ManagerFiltersTest extends TestCase
{
    /** @var App\Filters\ManagerFilters */
    protected $subject;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = app(ManagerFilters::class);
    }

    /** @test */
    public function manager_filters_include_filtering_by_status()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }

    /** @test */
    public function manager_filters_include_filtering_by_started_at_date()
    {
        $this->assertUsesTrait(FiltersByStartDate::class, $this->subject);
        $this->assertTrue(in_array('startedAt', $this->subject->filters));
    }
}
