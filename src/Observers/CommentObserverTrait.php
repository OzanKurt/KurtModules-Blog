<?php 

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Models\Comment;

trait CommentObserverTrait
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

            if (!method_exists(Comment::class, 'bootSoftDeletes')) {
                //
            }

            return true;
        });
    }
}
