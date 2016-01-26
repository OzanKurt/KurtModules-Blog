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

}
