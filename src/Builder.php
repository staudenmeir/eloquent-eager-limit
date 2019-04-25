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
     * @param int $value
     * @param string $column
     * @return $this
     */
    public function groupLimit($value, $column)
    {
        if ($value >= 0) {
            $this->groupLimit = compact('value', 'column');
        }

        return $this;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        $items = parent::get($columns);

        if (!$this->groupLimit) {
            return $items;
        }

        $column = last(explode('.', $this->groupLimit['column']));

        $keys = [
            'laravel_row',
            '@laravel_partition := '.$this->grammar->wrap($column),
            '@laravel_partition := '.$this->grammar->wrap('pivot_'.$column),
        ];

        foreach ($items as $item) {
            unset($item->{$keys[0]}, $item->{$keys[1]}, $item->{$keys[2]});
        }

        return $items;
    }
}
