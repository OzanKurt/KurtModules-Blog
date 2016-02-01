<?php

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository;

class CategoryObserver extends AbstractObserver
{
    public function created($model)
    {
        //
    }

    public function creating($model)
    {
        //
    }

    public function saved($model)
    {
        //
    }

    public function saving($model)
    {
        //
    }

    public function updated($model)
    {
        //
    }

    public function updating($category)
    {
        $this->clearCacheTags(CachingCategoriesRepository::class);
    }

    public function deleted($model)
    {
        //
    }

    public function deleting($category)
    {
        if (!$this->modelUsesSoftDeletes()) {
            $category->posts()->update([
                'category_id' => null,
            ]);
        }

        $this->clearCacheTags(CachingCategoriesRepository::class);
    }
}
