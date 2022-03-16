<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\PostgresGrammar as Base;
use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesPostgresGroupLimit;

class PostgresGrammar extends Base
{
    use CompilesPostgresGroupLimit;
}
