<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany as Base;

class HasMany extends Base
{
    use HasLimit;
}
