<?php

namespace Kurt\Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Observers\CategoryObserver;
use Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepository;
use Kurt\Modules\Blog\Repositories\Posts\EloquentPostsRepository;
use Kurt\Modules\Blog\Repositories\Tags\EloquentTagsRepository;

class BlogServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers\Blog';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->initConfig();

        $this->publishVendor();

        $this->registerRepositories();
    }

    /**
     * Merge configurations with the default config file.
     *
     * @return void
     */
    private function initConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/kurt_modules_blog.php', 'kurt_modules_blog');
    }

    /**
     * Publish required files.
     *
     * @return void
     */
    protected function publishVendor()
    {
        $this->publishConfigurations();

        $this->publishRoutes();

        $this->publishMigrations();
    }

    /**
     * Register repositories.
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->singleton(CategoriesRepository::class, function () {
            $eloquentCategoriesRepository = new EloquentCategoriesRepository(new Category);

            return $eloquentCategoriesRepository;

            $cachingCategoriesRepository = new CachingCategoriesRepository(
                $this->app->make('cache.store'),
                $eloquentCategoriesRepository
            );

            return $cachingCategoriesRepository;
        });
        $this->app->singleton(PostsRepository::class, EloquentPostsRepository::class);
        $this->app->singleton(TagsRepository::class, EloquentTagsRepository::class);
    }

    /**
     * Publish configurations.
     *
     * @return void
     */
    protected function publishConfigurations()
    {
        $this->publishes([
            __DIR__ . '/../../config/kurt_modules_blog.php' => config_path('kurt_modules_blog.php'),
        ], 'config');
    }

    /**
     * Publish routes.
     *
     * @return void
     */
    protected function publishRoutes()
    {
        $this->publishes([
            __DIR__ . '/../Http/blogRoutes.php' => $this->getBlogRoutesPath(),
        ], 'routes');
    }

    /**
     * Publish migrations.
     *
     * @return void
     */
    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => base_path('database/migrations'),
        ], 'migrations');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $blogRoutesPath = $this->getBlogRoutesPath();

        if (!$this->app->routesAreCached()) {
            if ($this->routesArePublished($blogRoutesPath)) {
                $router->group([
                    'namespace' => $this->namespace
                ], function ($router) use ($blogRoutesPath) {
                    require $blogRoutesPath;
                });
            } else {
                warning('KurtModules-Blog routes file is not published.');
            }
        }
    }

    /**
     * Get the blog_routes_path from configurations.
     *
     * @return string
     */
    private function getBlogRoutesPath()
    {
        return $this->app->config->get('kurt_modules_blog.blog_routes_path');
    }

    /**
     * Determine if the routes file is published.
     *
     * @param $blogRoutesPath
     * @return bool
     */
    protected function routesArePublished($blogRoutesPath)
    {
        return file_exists($blogRoutesPath);
    }
}
