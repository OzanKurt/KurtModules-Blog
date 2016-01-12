<?php

namespace Kurt\Modules\Blog\Traits;

trait CountFromRelationTrait
{
    /**
     * Gets the aggregate from a given relation.
     *
     * @param $relationName
     * @return int
     */
    private function getCountFromRelation($relationName)
    {
        if (!$this->relationLoaded($relationName)) {
            $this->load($relationName);
        }

        $related = $this->getRelation($relationName);

        return ($related) ? (int)$related->aggregate : 0;
    }
}