<?php

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository;

class CategoryObserver extends AbstractObserver
{
    public function created($model)
    {
        // TODO: Implement created() method.
    }

    public function creating($model)
    {
        // TODO: Implement creating() method.
    }

    public function saved($model)
    {
        // TODO: Implement saved() method.
    }

    public function saving($model)
    {
        // TODO: Implement saving() method.
    }

    public function updated($model)
    {
        // TODO: Implement updated() method.
    }

    public function updating($category)
    {
        $this->clearCacheTags(CachingCategoriesRepository::class);
    }

    public function deleted($model)
    {
        // TODO: Implement deleted() method.
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
