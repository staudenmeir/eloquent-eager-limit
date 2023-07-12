<?php

namespace Staudenmeir\EloquentEagerLimit;

use Illuminate\Database\Connection;
use RuntimeException;
use Staudenmeir\EloquentEagerLimit\Grammars\MySqlGrammar;
use Staudenmeir\EloquentEagerLimit\Grammars\PostgresGrammar;
use Staudenmeir\EloquentEagerLimit\Grammars\SQLiteGrammar;
use Staudenmeir\EloquentEagerLimit\Grammars\SqlServerGrammar;
use Staudenmeir\EloquentEagerLimit\Traits\HasEagerLimitRelationships;

trait HasEagerLimit
{
    use HasEagerLimitRelationships;

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        $grammar = $connection->withTablePrefix($this->getQueryGrammar($connection));

        return new \Staudenmeir\EloquentEagerLimit\Builder(
            $connection,
            $grammar,
            $connection->getPostProcessor()
        );
    }

    /**
     * Get the query grammar.
     *
     * @param \Illuminate\Database\Connection $connection
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    protected function getQueryGrammar(Connection $connection)
    {
        $driver = $connection->getDriverName();

        $grammar = match ($driver) {
            'mysql', 'mariadb' => new MySqlGrammar(),
            'pgsql' => new PostgresGrammar(),
            'sqlite' => new SQLiteGrammar(),
            'sqlsrv' => new SqlServerGrammar(),
            default => throw new RuntimeException('This database is not supported.'), // @codeCoverageIgnore
        };

        // TODO[L11]
        if (method_exists($grammar, 'setConnection')) {
            $grammar->setConnection($connection);
        }

        return $grammar;
    }
}
