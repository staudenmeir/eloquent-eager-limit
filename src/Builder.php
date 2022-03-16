<?php

namespace Staudenmeir\EloquentEagerLimit;

use Illuminate\Database\Query\Builder as Base;
use Staudenmeir\EloquentEagerLimit\Traits\BuildsGroupLimitQueries;

class Builder extends Base
{
    use BuildsGroupLimitQueries;
}
