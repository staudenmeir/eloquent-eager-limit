<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

use Illuminate\Database\Query\Grammars\MySqlGrammar;

trait BelongsOrMorphToMany
{
    use HasLimit;

    /**
     * Set the "limit" value of the query.
     *
     * @param int $value
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
                $column = 'pivot_'.last(explode('.', $column));
            }

            $this->query->groupLimit($value, $column);
        }

        return $this;
    }
}
