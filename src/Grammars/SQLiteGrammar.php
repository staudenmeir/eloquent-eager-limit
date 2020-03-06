<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as Base;
use PDO;

class SQLiteGrammar extends Base
{
    use CompilesGroupLimit {
        compileGroupLimit as compileGroupLimitParent;
    }

    /**
     * Compile a group limit clause.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return string
     */
    protected function compileGroupLimit(Builder $query)
    {
        $version = $query->getConnection()->getReadPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

        if (version_compare($version, '3.25.0') >= 0) {
            return $this->compileGroupLimitParent($query);
        }

        $query->groupLimit = null;

        return $this->compileSelect($query);
    }
}
