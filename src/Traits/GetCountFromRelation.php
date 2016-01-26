<?php

namespace Kurt\Modules\Blog\Traits;

trait GetCountFromRelation
{
    /**
     * Gets the aggregate from a given relation.
     *
     * @param string $relationName
     * @param string $value
     *
     * @return int
     */
    protected function getCountFromRelation($relationName, $value)
    {
        if (!is_null($value)) {
            return $value;
        }

        if (!$this->relationLoaded($relationName)) {
            $this->load($relationName);
        }

        $related = $this->getRelation($relationName);

        return ($related) ? (int)$related->aggregate : 0;
    }
}
