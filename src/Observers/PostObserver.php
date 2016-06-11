<?php

namespace Kurt\Modules\Blog\Observers;

class PostObserver extends AbstractObserver
{
    
    public function created($post)
    {
        //
    }

    public function creating($post)
    {
        //
    }

    public function saved($post)
    {
        //
    }

    public function saving($post)
    {
        //
    }

    public function updated($post)
    {
        //
    }

    public function updating($post)
    {
        //
    }

    public function deleted($post)
    {
        //
    }

    public function deleting($post)
    {
        if (!$this->modelUsesSoftDeletes($post)) {
            $post->comments()->delete();
        }
    }
}
