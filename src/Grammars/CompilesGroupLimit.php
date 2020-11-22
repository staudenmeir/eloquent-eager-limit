<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Builder;

trait CompilesGroupLimit
{
    /**
     * Compile a select query into SQL.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return string
     */
    public function compileSelect(Builder $query)
    {
        if (isset($query->groupLimit)) {
            if (is_null($query->columns)) {
                $query->columns = ['*'];
            }

            return $this->compileGroupLimit($query);
        }

        return parent::compileSelect($query);
    }

    /**
     * Compile a group limit clause.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return string
     */
    protected function compileGroupLimit(Builder $query)
    {
        $selectBindings = array_merge($query->getRawBindings()['select'], $query->getRawBindings()['order']);

        $query->setBindings($selectBindings, 'select');
        $query->setBindings([], 'order');

        $limit = (int) $query->groupLimit['value'];

        $offset = $query->offset;

        if (isset($offset)) {
            $limit += (int) $offset;

            $query->offset = null;
        }

        $components = $this->compileComponents($query);

        $components['columns'] .= $this->compileRowNumber($query->groupLimit['column'], $components['orders'] ?? '');

        unset($components['orders']);

        $sql = $this->concatenate($components);

        $sql = 'select * from ('.$sql.') as laravel_table where laravel_row <= '.$limit;

        if (isset($offset)) {
            $sql .= ' and laravel_row > '.(int) $offset;
        }

        return $sql.' order by laravel_row';
    }

    /**
     * Compile a row number clause.
     *
     * @param string $partition
     * @param string $orders
     * @return string
     */
    protected function compileRowNumber($partition, $orders)
    {
        $partition = 'partition by '.$this->wrap($partition);

        $over = trim($partition.' '.$orders);

        return ', row_number() over ('.$over.') as laravel_row';
    }
}
