<?php

namespace Kurt\Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Posts\EloquentPostsRepository;
use Kurt\Modules\Blog\Repositories\Tags\EloquentTagsRepository;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Default namespace for blog routes.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers\Blog';

    /**
     * Base path of blog module.
     *
     * @var string
     */
    protected $basePath = __DIR__.'/../..';

    /**
     * Source path of blog module.
     *
     * @var string
     */
    protected $sourcePath = __DIR__.'/..';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
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

        $this->publishViews();
    }

    /**
     * Merge configurations with the default config file.
     *
     * @return void
     */
    private function initConfig()
    {
        $this->mergeConfigFrom($this->basePath.'/config/kurt_modules_blog.php', 'kurt_modules_blog');
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
        $this->app->singleton(CategoriesRepositoryInterface::class, function() {
            return $this->instantiateCategoriesRepository();
        });
        $this->app->singleton(PostsRepositoryInterface::class, EloquentPostsRepository::class);
        $this->app->singleton(TagsRepositoryInterface::class, EloquentTagsRepository::class);
    }

    /**
     * Instantiate categories repository.
     *
     * @return \Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository|\Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository
     */
    function instantiateCategoriesRepository()
    {
        $eloquentCategoriesRepository = new EloquentCategoriesRepository(new Category());

        if ($this->getBlogDebug() || !$this->getBlogCache()) {
            return $eloquentCategoriesRepository;
        }

        $cachingCategoriesRepository = new CachingCategoriesRepository(
            $this->app->make('cache.store'),
            $eloquentCategoriesRepository
        );

        return $cachingCategoriesRepository;
    }

    /**
     * Publish views.
     *
     * @todo: Add ability to publish module views.
     *
     * @return void
     */
    private function publishViews()
    {
        $this->loadViewsFrom($this->basePath.'/resources/views', 'kurtmodules-blog');

        $this->publishes([
            $this->basePath.'/resources/views' => base_path('resources/views/vendor/kurtmodules-blog'),
        ]);
    }

    /**
     * Publish configurations.
     *
     * @return void
     */
    protected function publishConfigurations()
    {
        $this->publishes([
            $this->basePath.'config/kurt_modules_blog.php' => config_path('kurt_modules_blog.php'),
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
            $this->sourcePath.'/Http/blogRoutes.php' => $this->getBlogRoutesPath(),
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
            $this->basePath.'/database/migrations/' => base_path('database/migrations'),
        ], 'migrations');
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $blogRoutesPath = $this->getBlogRoutesPath();

        if (!$this->app->routesAreCached()) {
            if ($this->routesArePublished($blogRoutesPath)) {
                $router->group([
                    'namespace' => $this->namespace,
                ], function ($router) use ($blogRoutesPath) {
                    require $blogRoutesPath;
                });
            } else {
                $this->app->make('log')->error('KurtModules-Blog routes file is not published.');
            }
        }
    }

    /**
     * Get the `debug` from configurations.
     *
     * @return string
     */
    private function getBlogDebug()
    {
        return $this->app->make('config')->get('kurt_modules_blog.debug');
    }

    /**
     * Get the `cache` from configurations.
     *
     * @return string
     */
    private function getBlogCache()
    {
        return $this->app->make('config')->get('kurt_modules_blog.cache');
    }


    /**
     * Get the `blog_routes_path` from configurations.
     *
     * @return string
     */
    private function getBlogRoutesPath()
    {
        return $this->app->make('config')->get('kurt_modules_blog.blog_routes_path');
    }

    /**
     * Determine if the routes file is published.
     *
     * @param $blogRoutesPath
     *
     * @return bool
     */
    protected function routesArePublished($blogRoutesPath)
    {
        return file_exists($blogRoutesPath);
    }
}
