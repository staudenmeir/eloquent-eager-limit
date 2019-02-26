<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany as Base;

class MorphToMany extends Base
{
    use BelongsOrMorphToMany;
}
