<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as Base;

class BelongsToMany extends Base
{
    use BelongsOrMorphToMany;
}
