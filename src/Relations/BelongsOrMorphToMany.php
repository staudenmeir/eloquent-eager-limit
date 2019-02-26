<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Support\Str;
use Staudenmeir\EloquentEagerLimit\Grammars\MySqlGrammar;

trait BelongsOrMorphToMany
{
    use HasLimit;

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($value)
    {
        if ($this->parent->exists) {
            $this->query->limit($value);
        } else {
            $column = $this->getExistenceCompareKey();

            $grammar = $this->query->getQuery()->getGrammar();

            if ($grammar instanceof MySqlGrammar && $grammar->useLegacyGroupLimit($this->query->getQuery())) {
                $column = 'pivot_'.Str::after($column, '.');
            }

            $this->query->groupLimit($value, $column);
        }

        return $this;
    }
}
