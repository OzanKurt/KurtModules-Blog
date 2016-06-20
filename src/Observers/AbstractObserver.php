<?php

namespace Kurt\Modules\Blog\Observers;

use Cache;

abstract class AbstractObserver
{
    
    abstract public function created($model);

    abstract public function creating($model);

    abstract public function saved($model);

    abstract public function saving($model);

    abstract public function updated($model);

    abstract public function updating($model);

    abstract public function deleted($model);

    abstract public function deleting($model);

    /**
     * Flush cache for given tags.
     *
     * @param $tags array
     */
    protected function clearCacheTags($tags)
    {
        $cacheDriver = config('cache.default');

        if (in_array($cacheDriver, ['memcached', 'redis'])) {
            Cache::tags($tags)->flush();
        }
    }

    /**
     * Determine if model uses soft deletes.
     *
     * @param $model \Illuminate\Database\Eloquent\Model
     *
     * @return bool
     */
    protected function modelUsesSoftDeletes($model)
    {
        return method_exists(get_class($model), 'bootSoftDeletes');
    }
}
