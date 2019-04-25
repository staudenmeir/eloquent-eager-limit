<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\SqlServerGrammar as Base;

class SqlServerGrammar extends Base
{
    use CompilesGroupLimit {
        compileRowNumber as compileRowNumberParent;
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
        if (empty($orders)) {
            $orders = 'order by (select 0)';
        }

        return $this->compileRowNumberParent($partition, $orders);
    }
}
