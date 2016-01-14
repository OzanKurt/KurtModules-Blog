<?php

namespace Kurt\Modules\Blog\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepository;

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
     * @param PostsRepository $blogPostsRepository
     * @param TagsRepository $blogTagsRepository
     */
    public function __construct(
        CategoriesRepository $blogCategoriesRepository,
        PostsRepository $blogPostsRepository,
        TagsRepository $blogTagsRepository
    ) {
        $this->blogCategoriesRepository = $blogCategoriesRepository;
        $this->blogPostsRepository      = $blogPostsRepository;
        $this->blogTagsRepository       = $blogTagsRepository;
    }
}
