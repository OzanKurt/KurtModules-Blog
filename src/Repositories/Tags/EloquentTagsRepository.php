<?php

namespace Kurt\Modules\Blog\Repositories\Tags;

use Kurt\Modules\Blog\Models\Tag;
use Kurt\Modules\Blog\Repositories\Contracts\TagsRepository;

class EloquentTagsRepository implements TagsRepository
{
    /**
     * Model instance.
     *
     * @var Tag
     */
    protected $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return Tag
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a row by it's id with it's posts.
     *
     * @param $id
     *
     * @return Tag
     */
    public function findByIdWithPosts($id)
    {
        $tag = $this->findById($id);

        $tag->load(['posts.category', 'posts.tags']);

        return $tag;
    }

    /**
     * Find a row by it's id.
     *
     * @param $slug
     *
     * @return Tag
     */
    public function findBySlug($slug)
    {
        return $this->model->findBySlug($slug);
    }

    /**
     * Find a row by it's id with it's posts.
     *
     * @param $slug
     *
     * @return Tag
     */
    public function findBySlugWithPosts($slug)
    {
        $tag = $this->findBySlug($slug);

        $tag->load(['posts.category', 'posts.tags']);

        return $tag;
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
    public function getAllWithPosts()
    {
        $tags = $this->getAll();

        $tags->load(['posts.category', 'posts.tags']);

        return $tags;
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
    public function paginateAllWithPosts($postsPerPage)
    {
        $posts = $this->paginateAll($postsPerPage);

        $posts->load(['posts.category', 'posts.tags']);

        return $posts;
    }
}
