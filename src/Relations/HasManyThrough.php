<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough as Base;

class HasManyThrough extends Base
{
    use HasLimit;

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($value)
    {
        $this->query->groupLimit($value, $this->getQualifiedFirstKeyName());

        return $this;
    }
}
