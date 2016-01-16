<?php

namespace Kurt\Modules\Blog\Repositories\Posts;

use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;

class EloquentPostsRepository implements PostsRepository
{
    /**
     * Model instance.
     *
     * @var Post
     */
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return Post
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
     * @return Post
     */
    public function findByIdWithCategory($id)
    {
        return $this->model->with(['category'])->find($id);
    }

    /**
     * Find a row by it's id with it's category and tags.
     *
     * @param $id
     *
     * @return Post
     */
    public function findByIdWithCategoryAndTags($id)
    {
        return $this->model->with(['category', 'tags'])->find($id);
    }

    /**
     * Get all posts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all posts with it's category.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithCategory()
    {
        return $this->model->with(['category'])->get();
    }

    /**
     * Get all posts with it's category and tags.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithCategoryAndTags()
    {
        return $this->model->with(['category', 'tags'])->get();
    }

    /**
     * Paginate all posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateAllWithCategoryAndTags($postsPerPage)
    {
        $posts = $this->paginateAll($postsPerPage);

        $posts->load(['category', 'tags']);

        return $posts;
    }
}
