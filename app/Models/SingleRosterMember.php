<?php

namespace App\Models;

use App\Models\Contracts\Employable;
use App\Models\Contracts\Injurable;
use App\Models\Contracts\Reinstatable;
use App\Models\Contracts\Releasable;
use App\Models\Contracts\Retirable;
use App\Models\Contracts\Suspendable;
use App\Models\Contracts\Unretirable;
use Illuminate\Database\Eloquent\Model;

abstract class SingleRosterMember extends Model implements Employable, Injurable, Releasable, Reinstatable, Retirable, Suspendable, Unretirable
{
    use Concerns\Employable,
        Concerns\Injurable,
        Concerns\Releasable,
        Concerns\Reinstatable,
        Concerns\Retirable,
        Concerns\Suspendable;
}
