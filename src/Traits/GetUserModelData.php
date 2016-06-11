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
     * Get a new user model instance.
     *
     * @return mixed
     */
    protected function getUserModel()
    {
        return app(config('kurt_modules.user_model'));
    }
    
    /**
     * Get user model class name with namespace.
     *
     * @return string
     */
    protected function getUserModelClassName()
    {
        return config('kurt_modules.user_model');
    }

    /**
     * Get user model primary key.
     *
     * @return mixed
     */
    protected function getUserModelPrimaryKey()
    {
        return $this->getUserModel()->getKeyName();
    }

}
