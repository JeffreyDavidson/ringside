<?php

namespace Tests\Integration\Filters;

use App\Filters\Concerns\FiltersByStartDate;
use App\Filters\Concerns\FiltersByStatus;
use App\Filters\WrestlerFilters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * @group wrestlers
 * @group roster
 */
class WrestlerFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wrestlers_can_be_filtered_by_their_start_date()
    {
        $this->assertUsesTrait(FiltersByStatus::class, $this->subject);
        $this->assertTrue(in_array('status', $this->subject->filters));
    }
}
