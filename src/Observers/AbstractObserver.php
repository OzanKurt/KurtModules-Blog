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

    protected function clearCacheTags($tags)
    {
        Cache::tags($tags)->flush();
    }

    protected function modelUsesSoftDeletes($model)
    {
        return method_exists(get_class($model), 'bootSoftDeletes');
    }
}
