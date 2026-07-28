<?php

declare(strict_types=1);

namespace Kurt\Modules\Blog\Observers;

use Kurt\Modules\Blog\Events\CategoryCreated;
use Kurt\Modules\Blog\Events\CategoryDeleted;
use Kurt\Modules\Blog\Events\CategoryUpdated;
use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Observers\Concerns\InvalidatesBlogCache;

final class CategoryObserver
{
    use InvalidatesBlogCache;

    public function created(Category $category): void
    {
        CategoryCreated::dispatch($category);

        $this->forgetTaxonomyCaches();
    }

    public function updated(Category $category): void
    {
        CategoryUpdated::dispatch($category);

        $this->forgetTaxonomyCaches();
    }

    public function deleted(Category $category): void
    {
        CategoryDeleted::dispatch($category);

        $this->forgetTaxonomyCaches();
    }

    /**
     * A category write can change the sitemap's category URLs and the feed's
     * per-item `<category>` labels, so both are invalidated.
     */
    private function forgetTaxonomyCaches(): void
    {
        $this->forgetSitemap();
        $this->forgetFeed();
    }
}
