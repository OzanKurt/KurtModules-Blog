<?php

namespace Kurt\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;

use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepositoryInterface;

/**
 * Class BlogController
 *
 * @package Kurt\Modules\Blog\Http\Controllers
 */
class BlogController extends Controller
{
    protected $blogCategoriesRepository;
    protected $blogPostsRepository;
    protected $blogTagsRepository;

    /**
     * Ready up blog repositories.
     */
    public function __construct()
    {
        $this->blogCategoriesRepository = app(CategoriesRepositoryInterface::class);
        $this->blogPostsRepository = app(PostsRepositoryInterface::class);
        $this->blogTagsRepository = app(TagsRepositoryInterface::class);

        $this->callConstructor();
    }

    public function callConstructor()
    {
        $parent = new \ReflectionClass(parent::class);

        if ($parent->getConstructor()) {
            parent::__construct();
        }
    }
}
