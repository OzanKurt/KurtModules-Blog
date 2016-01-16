<?php


namespace Kurt\Modules\Blog\Observers;

class TagObserver extends AbstractObserver
{
    public function created($tag)
    {
        // TODO: Implement created() method.
    }

    public function creating($tag)
    {
        // TODO: Implement creating() method.
    }

    public function saved($tag)
    {
        // TODO: Implement saved() method.
    }

    public function saving($tag)
    {
        // TODO: Implement saving() method.
    }

    public function updated($tag)
    {
        // TODO: Implement updated() method.
    }

    public function updating($tag)
    {
        // TODO: Implement updating() method.
    }

    public function deleted($tag)
    {
        // TODO: Implement deleted() method.
    }

    public function deleting($tag)
    {
        if (!$this->modelUsesSoftDeletes($tag)) {
            $tag->posts()->sync([]);
        }
    }
}
