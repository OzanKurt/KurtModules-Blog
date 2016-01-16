<?php


namespace Kurt\Modules\Blog\Observers;

class CommentObserver extends AbstractObserver
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

    public function updating($model)
    {
        // TODO: Implement updating() method.
    }

    public function deleted($model)
    {
        // TODO: Implement deleted() method.
    }

    public function deleting($post)
    {
        if (!$this->modelUsesSoftDeletes()) {
            //
        }
    }
}
