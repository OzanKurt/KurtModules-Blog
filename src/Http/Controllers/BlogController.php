<?php

namespace Kurt\Modules\Blog\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepository;

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
     * @param CategoriesRepository $blogCategoriesRepository
     * @param PostsRepository      $blogPostsRepository
     * @param TagsRepository       $blogTagsRepository
     */
    public function __construct(
        CategoriesRepository $blogCategoriesRepository,
        PostsRepository $blogPostsRepository,
        TagsRepository $blogTagsRepository
    ) {
        $this->blogCategoriesRepository = $blogCategoriesRepository;
        $this->blogPostsRepository = $blogPostsRepository;
        $this->blogTagsRepository = $blogTagsRepository;
    }
}
