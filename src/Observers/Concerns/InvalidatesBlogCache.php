<?php

declare(strict_types=1);

namespace Kurt\Modules\Blog\Observers\Concerns;

use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Support\Concerns\ResolvesBlogCache;

/**
 * Cache-busting helpers shared by the Blog observers. They forget the same
 * `blog`-prefixed keys the read paths (SitemapBuilder, FeedBuilder,
 * PostController) populate, so a real write is reflected on the next read
 * instead of waiting out the TTL.
 */
trait InvalidatesBlogCache
{
    use ResolvesBlogCache;

    /**
     * Forget the aggregate sitemap entries (both the default and
     * `includeTags` variants).
     */
    protected function forgetSitemap(): void
    {
        $this->blogCache()->forget('sitemap');
        $this->blogCache()->forget('sitemap:tags');
    }

    /**
     * Forget the canonical feed. The feed embeds each post's category name, so
     * taxonomy edits invalidate it too.
     */
    protected function forgetFeed(): void
    {
        $this->blogCache()->forget('feed');
    }

    /**
     * Forget every by-slug/by-id entry a post could have been cached under,
     * including the pre-save slug when a title edit regenerated it.
     */
    protected function forgetPost(Post $post): void
    {
        $keys = [(string) $post->getKey(), (string) $post->slug];

        $originalSlug = $post->getOriginal('slug');
        if (is_string($originalSlug) && $originalSlug !== '') {
            $keys[] = $originalSlug;
        }

        foreach (array_unique($keys) as $key) {
            $this->blogCache()->forget("post:{$key}");
        }
    }
}
