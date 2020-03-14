<?php

namespace Tests\Fixtures;

use App\Filters\Concerns\FiltersByStartDate;
use App\Filters\Filters;

class ExampleFilters extends Filters
{
    use FiltersByStartDate;
}
