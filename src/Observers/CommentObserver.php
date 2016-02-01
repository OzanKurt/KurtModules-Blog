<?php


namespace Kurt\Modules\Blog\Observers;

class CommentObserver extends AbstractObserver
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

    public function updating($model)
    {
        //
    }

    public function deleted($model)
    {
        //
    }

    public function deleting($post)
    {
        if (!$this->modelUsesSoftDeletes()) {
            //
        }
    }
}
