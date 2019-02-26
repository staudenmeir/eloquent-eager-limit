<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneThrough as Base;

class HasOneThrough extends Base
{
    use HasOneOrManyThrough;
}
