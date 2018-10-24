<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\PostgresGrammar as Base;

class PostgresGrammar extends Base
{
    use CompilesGroupLimit;
}
