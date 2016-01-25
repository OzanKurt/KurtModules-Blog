<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BlogModel
 *
 * @package Kurt\Modules\Blog\Models
 */
class BlogModel extends Model
{
    /**
     * Todo: Description.
     *
     * @return mixed
     */
    protected function getUserModelClass()
    {
        return config('auth.providers.users.model');
    }

    /**
     * Todo: Description.
     *
     * @return mixed
     */
    protected function getUserModelPrimaryKey()
    {
        return app($this->getUserModelClass())->getKeyName();
    }

    /**
     * Gets the aggregate from a given relation.
     *
     * @param $relationName
     * @param $value
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

        return ($related) ? (int) $related->aggregate : 0;
    }
}
