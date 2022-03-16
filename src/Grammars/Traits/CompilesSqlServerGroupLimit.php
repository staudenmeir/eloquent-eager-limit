<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars\Traits;

trait CompilesSqlServerGroupLimit
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
