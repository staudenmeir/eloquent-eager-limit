<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\SqlServerGrammar as Base;
use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesSqlServerGroupLimit;

class SqlServerGrammar extends Base
{
    use CompilesSqlServerGroupLimit;
}
