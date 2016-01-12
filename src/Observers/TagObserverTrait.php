<?php 

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Models\Tag;

trait TagObserverTrait
{

    /**
     * Boot the post model
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {

            if (!method_exists(Tag::class, 'bootSoftDeletes')) {
                $post->posts()->sync([]);
            }

            return true;
        });
    }
}
