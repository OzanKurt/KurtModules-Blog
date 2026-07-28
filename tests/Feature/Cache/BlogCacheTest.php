<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Support\FeedBuilder;
use Kurt\Modules\Blog\Support\SitemapBuilder;
use Kurt\Modules\Blog\Tests\Stubs\StubUser;
use Kurt\Modules\Core\Support\ModuleCacheFactory;

beforeEach(function () {
    // Pin the module cache to the in-memory array store so the assertions are
    // driver-agnostic and cache traffic never shows up in the query log.
    config()->set('blog.cache.enabled', true);
    config()->set('blog.cache.store', 'array');

    $this->user = StubUser::create(['email' => 'author@example.com']);

    $this->blogCache = app(ModuleCacheFactory::class)->for('blog');
});

it('caches the sitemap build so a second read hits no database', function () {
    Post::factory()->published()->create(['user_id' => $this->user->id]);

    DB::flushQueryLog();
    DB::enableQueryLog();

    SitemapBuilder::make()->entries();
    $firstReadQueries = count(DB::getQueryLog());

    DB::flushQueryLog();
    SitemapBuilder::make()->entries();
    $secondReadQueries = count(DB::getQueryLog());

    expect($firstReadQueries)->toBeGreaterThan(0)
        ->and($secondReadQueries)->toBe(0);
});

it('caches the feed build so a second read hits no database', function () {
    Post::factory()->published()->create(['user_id' => $this->user->id]);

    DB::flushQueryLog();
    DB::enableQueryLog();

    FeedBuilder::make()->posts();
    $firstReadQueries = count(DB::getQueryLog());

    DB::flushQueryLog();
    FeedBuilder::make()->posts();
    $secondReadQueries = count(DB::getQueryLog());

    expect($firstReadQueries)->toBeGreaterThan(0)
        ->and($secondReadQueries)->toBe(0);
});

it('busts the sitemap and post-by-slug caches on a real content edit', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'title' => ['en' => 'Cached Title'],
    ]);
    $slug = $post->slug;

    // Prime both the aggregate sitemap and the by-slug entry.
    SitemapBuilder::make()->entries();
    $this->blogCache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());

    // A real edit to the body (slug-preserving) must invalidate both.
    $post->update(['body' => ['en' => 'Rewritten body']]);

    DB::flushQueryLog();
    DB::enableQueryLog();
    SitemapBuilder::make()->entries();
    expect(count(DB::getQueryLog()))->toBeGreaterThan(0);

    $resolved = $this->blogCache->remember(
        "post:{$slug}",
        fn () => Post::query()->where('slug', $slug)->first(),
    );

    expect($resolved->body)->toBe('Rewritten body');
});

it('does not bust the post-by-slug cache on a view-only bump but does on a real edit', function () {
    $post = Post::factory()->published()->create([
        'user_id' => $this->user->id,
        'title' => ['en' => 'Regression Post'],
    ]);
    $slug = $post->slug;

    // Prime the by-slug entry (view_count starts at 0).
    $primed = $this->blogCache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());
    expect($primed->view_count)->toBe(0);

    // Recording a view increments the DB counter but must NOT bust the cache:
    // the observer treats a view-only bump as non-content and forgets nothing.
    Post::query()->findOrFail($post->id)->recordView('203.0.113.9');
    expect(Post::query()->findOrFail($post->id)->view_count)->toBe(1);

    $afterView = $this->blogCache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());
    expect($afterView->view_count)->toBe(0); // still the cached, tick-stale value

    // A subsequent real edit DOES bust it, so the next read recomputes and now
    // reflects both the edit and the accumulated view_count.
    Post::query()->findOrFail($post->id)->update(['excerpt' => ['en' => 'Edited excerpt']]);

    $afterEdit = $this->blogCache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());
    expect($afterEdit->view_count)->toBe(1)
        ->and($afterEdit->excerpt)->toBe('Edited excerpt');
});

it('bypasses the cache entirely when blog.cache.enabled is false', function () {
    config()->set('blog.cache.enabled', false);
    $cache = app(ModuleCacheFactory::class)->for('blog');

    $post = Post::factory()->published()->create(['user_id' => $this->user->id]);
    $slug = $post->slug;

    $cache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());

    // An out-of-band write is reflected on the very next read because caching
    // is disabled, so the callback always runs.
    DB::table('blog_posts')->where('id', $post->id)->update(['view_count' => 99]);

    $reread = $cache->remember("post:{$slug}", fn () => Post::query()->where('slug', $slug)->first());

    expect($reread->view_count)->toBe(99);
});
