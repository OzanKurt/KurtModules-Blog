<?php

namespace Kurt\Modules\Blog\Repositories\Posts;

use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;

class EloquentPostsRepository implements PostsRepository
{
    /**
     * Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder|Post
     */
    protected $model;

    /**
     * EloquentPostsRepository constructor.
     *
     * @param \Kurt\Modules\Blog\Models\Post $model
     */
    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a row by it's id with it's category.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findByIdWithCategory($id)
    {
        $post = $this->findById($id);

        $post->load(['category']);

        return $post;
    }

    /**
     * Find a row by it's id with it's category and tags.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findByIdWithCategoryAndTags($id)
    {
        $post = $this->findById($id);

        $post->load(['category', 'tags']);

        return $post;
    }

    /**
     * Find a row by it's slug.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlug($slug)
    {
        return $this->model->where('slug', '=', $slug)->first();
    }

    /**
     * Find a row by it's slug with it's category.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlugWithCategory($slug)
    {
        $post = $this->findBySlug($slug);

        $post->load(['category']);

        return $post;
    }

    /**
     * Find a row by it's slug with it's category and tags.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlugWithCategoryAndTags($slug)
    {
        $post = $this->findBySlug($slug);

        $post->load(['category', 'tags']);

        return $post;
    }

    /**
     * Get all posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all posts with it's category.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithCategory()
    {
        $posts = $this->getAll();

        $posts->load(['category']);

        return $posts;
    }

    /**
     * Get all posts with it's category and tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithCategoryAndTags()
    {
        $posts = $this->getAll();

        $posts->load(['category', 'tags']);

        return $posts;
    }

    /**
     * Paginate all posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAll($postsPerPage)
    {
        return $this->model->paginate($postsPerPage);
    }

    /**
     * Paginate all posts with it's category.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllWithCategory($postsPerPage)
    {
        $posts = $this->paginateAll($postsPerPage);

        $posts->load(['category']);

        return $posts;
    }

    /**
     * Paginate all posts with it's category and tags.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllWithCategoryAndTags($postsPerPage)
    {
        $posts = $this->paginateAll($postsPerPage);

        $posts->load(['category', 'tags']);

        return $posts;
    }

    /**
     * Get all deleted posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeleted()
    {
        return $this->model->withTrashed()->get();
    }

    /**
     * Get all deleted posts with it's category.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeletedWithCategory()
    {
        $posts = $this->getAllDeleted();

        $posts->load(['category']);

        return $posts;
    }

    /**
     * Get all deleted posts with it's category and tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeletedWithCategoryAndTags()
    {
        $posts = $this->getAllDeleted();

        $posts->load(['category', 'tags']);

        return $posts;
    }

    /**
     * Paginate all deleted posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeleted($postsPerPage)
    {
        return $this->model->withTrashed()->paginate($postsPerPage);
    }

    /**
     * Paginate all deleted posts with it's category.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeletedWithCategory($postsPerPage)
    {
        $posts = $this->paginateAllDeleted();

        $posts->load(['category']);

        return $posts;
    }

    /**
     * Paginate all deleted posts with it's category and tags.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeletedWithCategoryAndTags($postsPerPage)
    {
        $posts = $this->paginateAllDeleted();

        $posts->load(['category', 'tags']);

        return $posts;
    }
}
