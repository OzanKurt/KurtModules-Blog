<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

interface PostsRepository
{
    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($id);

    /**
     * Find a row by it's id with it's category.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByIdWithCategory($id);

    /**
     * Find a row by it's id with it's category and tags.
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByIdWithCategoryAndTags($id);

    /**
     * Get all posts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll();

    /**
     * Get all posts with it's category.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithCategory();

    /**
     * Get all posts with it's category and tags.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithCategoryAndTags();

    /**
     * Paginate all posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Support\Collection
     */
    public function paginateAll($postsPerPage);

    /**
     * Paginate all posts with it's category.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Support\Collection
     */
    public function paginateAllWithCategory($postsPerPage);

    /**
     * Paginate all posts with it's category and tags.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Support\Collection
     */
    public function paginateAllWithCategoryAndTags($postsPerPage);
}
