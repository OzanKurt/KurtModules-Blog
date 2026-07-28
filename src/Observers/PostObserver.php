<?php

declare(strict_types=1);

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Enums\PostStatus;
use Kurt\Modules\Blog\Events\PostArchived;
use Kurt\Modules\Blog\Events\PostCreated;
use Kurt\Modules\Blog\Events\PostPublished;
use Kurt\Modules\Blog\Events\PostUpdated;
use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Observers\Concerns\InvalidatesBlogCache;

final class PostObserver
{
    use InvalidatesBlogCache;

    public function saving(Post $post): void
    {
        // Backfill the publish timestamp when a post is set Published without
        // an explicit published_at (e.g. the manual "Published" toggle in the
        // Filament editor). A saving hook covers every Filament version and
        // keeps scopePublished()/PostPolicy::view() treating it as live at
        // once instead of leaving it invisible behind a null published_at.
        if ($post->status === PostStatus::Published && $post->published_at === null) {
            $post->published_at = now();
        }
    }

    public function created(Post $post): void
    {
        PostCreated::dispatch($post);

        // A new post can appear in the aggregate reads, and its slug/id may
        // have been negatively cached before it existed.
        $this->forgetSitemap();
        $this->forgetFeed();
        $this->forgetPost($post);
    }

    public function updated(Post $post): void
    {
        // Skip PostUpdated when the only mutation is a recorded view, so
        // analytics bumps do not masquerade as content edits.
        $changed = array_keys($post->getChanges());
        $viewOnly = $changed !== [] && array_diff($changed, ['view_count', 'last_viewer_ip', 'updated_at']) === [];

        if (! $viewOnly) {
            PostUpdated::dispatch($post);

            // A real content edit (or a publish/archive transition) invalidates
            // the by-slug entry and the aggregate reads. A view-only bump busts
            // nothing, so view_count stays a tick stale by design.
            $this->forgetPost($post);
            $this->forgetSitemap();
            $this->forgetFeed();
        }

        if ($post->wasChanged('status')) {
            match ($post->status) {
                PostStatus::Published => PostPublished::dispatch($post),
                PostStatus::Archived => PostArchived::dispatch($post),
                default => null,
            };
        }
    }

    public function deleted(Post $post): void
    {
        $this->forgetPost($post);
        $this->forgetSitemap();
        $this->forgetFeed();
    }
}
