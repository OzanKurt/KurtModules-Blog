<?php

declare(strict_types=1);

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Events\TagCreated;
use Kurt\Modules\Blog\Events\TagDeleted;
use Kurt\Modules\Blog\Events\TagUpdated;
use Kurt\Modules\Blog\Models\Tag;
use Kurt\Modules\Blog\Observers\Concerns\InvalidatesBlogCache;

final class TagObserver
{
    use InvalidatesBlogCache;

    public function created(Tag $tag): void
    {
        TagCreated::dispatch($tag);

        $this->forgetSitemap();
    }

    public function updated(Tag $tag): void
    {
        TagUpdated::dispatch($tag);

        $this->forgetSitemap();
    }

    public function deleted(Tag $tag): void
    {
        TagDeleted::dispatch($tag);

        // Tags surface only in the sitemap's `includeTags` variant, never in
        // the feed, so only the sitemap is invalidated.
        $this->forgetSitemap();
    }
}
