<?php 

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Models\Post;

trait PostObserverTrait
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

            if (!method_exists(Post::class, 'bootSoftDeletes')) {
                $post->comments()->delete();
            }

            return true;
        });
    }
}
