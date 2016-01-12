<?php

namespace Kurt\Modules\Blog\Repositories\Posts;

use Kurt\Modules\Blog\Models\Post;
use Kurt\Modules\Blog\Repositories\Contracts\PostsRepository;

class EloquentPostsRepository implements PostsRepository
{

    /**
     * Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    function __construct(Post $model)
    {
        $this->model = $model;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a row by it's id with it's category.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByIdWithCategory($id)
    {
        return $this->model->with(['category'])->find($id);
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
     * Paginate all posts.
     *
     * @param integer $postsPerPage
     * @return \Illuminate\Support\Collection
     */
    public function paginateAll($postsPerPage)
    {
        return $this->model->paginate($postsPerPage);
    }

    /**
     * Paginate all posts with it's category.
     *
     * @param integer $postsPerPage
     * @return \Illuminate\Support\Collection
     */
    public function paginateAllWithCategory($postsPerPage)
    {
        $posts = $this->model->paginate($postsPerPage);

        $posts->load(['category']);

        return $posts;
    }

}
