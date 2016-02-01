<?php

namespace Kurt\Modules\Blog\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepositoryInterface;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepositoryInterface;

/**
 * Class BlogController
 * @package Kurt\Modules\Blog\Http\Controllers
 */
class BlogController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $blogCategoriesRepository;
//    protected $blogCommentsRepository;
    protected $blogPostsRepository;
    protected $blogTagsRepository;

    /**
     * Ready up blog repositories.
     *
     * @param CategoriesRepositoryInterface $blogCategoriesRepository
     * @param PostsRepositoryInterface      $blogPostsRepository
     * @param TagsRepositoryInterface       $blogTagsRepository
     */
    public function __construct(
        CategoriesRepositoryInterface $blogCategoriesRepository,
        PostsRepositoryInterface $blogPostsRepository,
        TagsRepositoryInterface $blogTagsRepository
    ) {
        $this->blogCategoriesRepository = $blogCategoriesRepository;
        $this->blogPostsRepository = $blogPostsRepository;
        $this->blogTagsRepository = $blogTagsRepository;
    }
}
