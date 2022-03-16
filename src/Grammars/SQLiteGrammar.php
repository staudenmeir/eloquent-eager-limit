<?php

namespace Staudenmeir\EloquentEagerLimit\Grammars;

use Illuminate\Database\Query\Grammars\SQLiteGrammar as Base;
use Staudenmeir\EloquentEagerLimit\Grammars\Traits\CompilesSQLiteGroupLimit;

class SQLiteGrammar extends Base
{
    use CompilesSQLiteGroupLimit;
}
