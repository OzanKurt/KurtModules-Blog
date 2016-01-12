<?php 

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Models\Category;

trait CategoryObserverTrait
{

    /**
     * Boot the category model
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {

            if (!method_exists(Category::class, 'bootSoftDeletes')) {
                $category->posts()->update([
                    'category_id' => null
                ]);
            }

            return true;
        });
    }
}
