<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\MySqlGrammar as Base;
use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesMySqlGroupLimit;

class MySqlGrammar extends Base
{
    use CompilesMySqlGroupLimit;
}
