<?php

namespace Staudenmeir\EloquentEagerLimit;

use Illuminate\Database\Query\Builder as Base;

class Builder extends Base
{
    /**
     * The maximum number of records to return per group.
     *
     * @var array
     */
    public $groupLimit;

    /**
     * Add a "group limit" clause to the query.
     *
     * @param  int  $value
     * @param  string  $column
     * @return $this
     */
    public function groupLimit($value, $column)
    {
        if ($value >= 0) {
            $this->groupLimit = compact('value', 'column');
        }

        return $this;
    }
}
