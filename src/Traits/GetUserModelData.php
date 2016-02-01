<?php

namespace Kurt\Modules\Blog\Traits;

/**
 * Trait GetUserModelData
 *
 * @package Kurt\Modules\Blog\Traits
 */
trait GetUserModelData
{
    /**
     * Get user model class name with namespace.
     *
     * @return mixed
     */
    protected function getUserModelClass()
    {
        return config('auth.providers.users.model');
    }

    /**
     * Get user model primary key.
     *
     * @return mixed
     */
    protected function getUserModelPrimaryKey()
    {
        return app($this->getUserModelClass())->getKeyName();
    }

}
