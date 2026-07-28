<?php

declare(strict_types=1);

namespace Kurt\Modules\Blog\Support\Concerns;

use Kurt\Modules\Core\Contracts\ModuleCache;
use Kurt\Modules\Core\Support\ModuleCacheFactory;

/**
 * Resolves the Blog module's scoped {@see ModuleCache} once per host object.
 * Both the read paths (builders, controller) and the invalidating observers
 * share this so they read and bust the same `blog`-prefixed store.
 */
trait ResolvesBlogCache
{
    private ?ModuleCache $blogCache = null;

    protected function blogCache(): ModuleCache
    {
        return $this->blogCache ??= app(ModuleCacheFactory::class)->for('blog');
    }
}
