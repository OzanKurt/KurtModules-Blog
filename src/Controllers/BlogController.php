<?php

namespace Kurt\Modules\Blog\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;

class BlogController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $blogCategoriesRepository;
    // protected $blogCommentsRepository;
    protected $blogPostsRepository;
    // protected $blogTagsRepository;

    /**
     * [__construct description]
     * @param PostsRepository $blogPostsRepository
     * @param CategoriesRepository $blogCategoriesRepository
     */
    public function __construct(
        CategoriesRepository $blogCategoriesRepository,
        PostsRepository $blogPostsRepository
    ) {
        $this->blogCategoriesRepository = $blogCategoriesRepository;
        $this->blogPostsRepository = $blogPostsRepository;
    }
}
