<?php


namespace Kurt\Modules\Blog\Observers;

class TagObserver extends AbstractObserver
{
    
    public function created($tag)
    {
        //
    }

    public function creating($tag)
    {
        //
    }

    public function saved($tag)
    {
        //
    }

    public function saving($tag)
    {
        //
    }

    public function updated($tag)
    {
        //
    }

    public function updating($tag)
    {
        //
    }

    public function deleted($tag)
    {
        //
    }

    public function deleting($tag)
    {
        if (!$this->modelUsesSoftDeletes($tag)) {
            $tag->posts()->sync([]);
        }
    }
}
