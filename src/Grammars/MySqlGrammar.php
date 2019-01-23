<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar as Base;
use Illuminate\Support\Str;
use PDO;

class MySqlGrammar extends Base
{
    use CompilesGroupLimit {
        compileGroupLimit as compileGroupLimitParent;
    }

    /**
     * Compile a group limit clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileGroupLimit(Builder $query)
    {
        $version = $query->getConnection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

        if (version_compare($version, '8.0.11') >= 0 || Str::contains($version, 'MariaDB')) {
            return $this->compileGroupLimitParent($query);
        }

        return $this->compileLegacyGroupLimit($query);
    }

    /**
     * Compile a group limit clause for MySQL < 8.0.
     *
     * Derived from https://softonsofa.com/tweaking-eloquent-relations-how-to-get-n-related-models-per-parent/.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileLegacyGroupLimit(Builder $query)
    {
        $limit = (int) $query->groupLimit['value'];

        $offset = $query->offset;

        if (isset($offset)) {
            $limit += (int) $offset;

            $query->offset = null;
        }

        $column = Str::after($query->groupLimit['column'], '.');

        if ($query->joins && Str::contains(end($query->columns), ' as pivot_')) {
            $column = 'pivot_'.$column;
        }

        $column = $this->wrap($column);

        $partition = ', @laravel_row := if(@laravel_partition = '.$column.', @laravel_row + 1, 1) as laravel_row';

        $partition .= ', @laravel_partition := '.$column;

        $orders = (array) $query->orders;

        array_unshift($orders, ['column' => $query->groupLimit['column'], 'direction' => 'asc']);

        $query->orders = $orders;

        $components = $this->compileComponents($query);

        $sql = $this->concatenate($components);

        $from = '(select @laravel_row := 0, @laravel_partition := 0) as laravel_vars, ('.$sql.') as laravel_table';

        $sql = 'select laravel_table.*'.$partition.' from '.$from.' having laravel_row <= '.$limit;

        if (isset($offset)) {
            $sql .= ' and laravel_row > '.(int) $offset;
        }

        return $sql.' order by laravel_row';


    }
}
