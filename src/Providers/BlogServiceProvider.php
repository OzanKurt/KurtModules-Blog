<?php

namespace Kurt\Modules\Blog\Providers;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

use Kurt\Modules\Blog\Console\Commands\SeedCommand;
use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Models\Post;

use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepositoryInterface;

use Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository;
use Kurt\Modules\Blog\Repositories\Posts\EloquentPostsRepository;
use Kurt\Modules\Blog\Repositories\Tags\EloquentTagsRepository;

use Kurt\Modules\Core\Traits\GetUserModelData;

use Illuminate\Foundation\AliasLoader;

use GrahamCampbell\Markdown\Facades\Markdown;
use GrahamCampbell\Markdown\MarkdownServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    use GetUserModelData;

    /**
     * Default namespace for blog routes.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

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

        $this->registerFactories();

        $this->registerCommands();

        $this->registerVendors();
    }

    /**
     * Merge configurations with the default config file.
     *
     * @return void
     */
    private function initConfig()
    {
        $this->mergeConfigFrom($this->basePath.'/config/kurt_modules.php', 'kurt_modules');
    }

    /**
     * Publish required files.
     *
     * @return void
     */
    private function publishVendor()
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
    private function registerRepositories()
    {
        $this->app->bind(CategoriesRepositoryInterface::class, function() {
            return $this->instantiateCategoriesRepository();
        });
        $this->app->bind(PostsRepositoryInterface::class, EloquentPostsRepository::class);
        $this->app->bind(TagsRepositoryInterface::class, EloquentTagsRepository::class);
    }

    /**
     * Instantiate categories repository.
     *
     * @return \Kurt\Modules\Blog\Repositories\Categories\CachingCategoriesRepository|\Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository
     */
    private function instantiateCategoriesRepository()
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

    private function registerFactories()
    {
        $factory = $this->app->make(Factory::class);
        
        $factory->define(Category::class, function(Faker $faker) {
            $name = $faker->colorName;
            return [
                'name' => $name,
                'slug' => str_slug($name),
            ];
        });
        
        $factory->define(Post::class, function(Faker $faker) {
            $userIds = $this->getUserModel()->lists('id')->toArray();
            $categoryIds = Category::lists('id')->toArray();

            $name = $faker->realText(40);

            return [
                'title' => $name,
                'slug' => str_slug($name),
                'type' => Post::TYPE_TEXT,
                'content' => $faker->realText(400),
                'user_id' => $faker->randomElement($userIds),
                'category_id' => $faker->randomElement($categoryIds),
                'published_at' => Carbon::now()->addDays(rand(0, 2)),
            ];
        });
    }

    private function registerCommands()
    {
        $this->app->singleton('command.kurtmodules-blog.seed', function () {
            return new SeedCommand();
        });

        $this->commands('command.kurtmodules-blog.seed');
    }

    public function registerVendors()
    {
        $this->app->register(MarkdownServiceProvider::class);

        $loader = AliasLoader::getInstance();

        $loader->alias('Markdown', Markdown::class);
    }

    /**
     * Publish configurations.
     *
     * @return void
     */
    private function publishConfigurations()
    {
        $this->publishes([
            $this->basePath.'/config/kurt_modules.php' => config_path('kurt_modules.php'),
        ], 'config');
    }

    /**
     * Publish routes.
     *
     * @return void
     */
    private function publishRoutes()
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
    private function publishMigrations()
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
        return $this->app->make('config')->get('kurt_modules.debug');
    }


    /**
     * Get the `cache` from configurations.
     *
     * @return string
     */
    private function getBlogCache()
    {
        return $this->app->make('config')->get('kurt_modules.blog.cache');
    }

    /**
     * Get the `kurt_modules.blog.routes_path` from configurations.
     *
     * @return string
     */
    private function getBlogRoutesPath()
    {
        return $this->app->make('config')->get('kurt_modules.blog.routes_path');
    }

    /**
     * Determine if the routes file is published.
     *
     * @param $blogRoutesPath
     *
     * @return bool
     */
    private function routesArePublished($blogRoutesPath)
    {
        return file_exists($blogRoutesPath);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.kurtmodules-blod.seed'];
    }
}
