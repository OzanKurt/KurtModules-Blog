<?php

namespace Kurt\Modules\Blog\Observers;

class PostObserver extends AbstractObserver
{

    public function created($post)
    {
        // TODO: Implement created() method.
    }

    public function creating($post)
    {
        // TODO: Implement creating() method.
    }

    public function saved($post)
    {
        // TODO: Implement saved() method.
    }

    public function saving($post)
    {
        // TODO: Implement saving() method.
    }

    public function updated($post)
    {
        // TODO: Implement updated() method.
    }

    public function updating($post)
    {
        // TODO: Implement updating() method.
    }

    public function deleted($post)
    {
        // TODO: Implement deleted() method.
    }

    public function deleting($post)
    {
        if (!$this->modelUsesSoftDeletes($post)) {
            $post->comments()->delete();
        }
    }
}
