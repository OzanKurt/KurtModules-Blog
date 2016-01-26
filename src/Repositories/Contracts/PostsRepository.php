<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

use Kurt\Modules\Blog\Models\Post;

interface PostsRepository
{

    /**
     * PostsRepository constructor.
     *
     * @param \Kurt\Modules\Blog\Models\Post $model
     */
    public function __construct(Post $model);

    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findById($id);

    /**
     * Find a row by it's id with it's category.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findByIdWithCategory($id);

    /**
     * Find a row by it's id with it's category and tags.
     *
     * @param $id
     */
    public function findByIdWithCategoryAndTags($id);

    /**
     * Find a row by it's slug.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlug($slug);

    /**
     * Find a row by it's slug with it's category.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlugWithCategory($slug);

    /**
     * Find a row by it's slug with it's category and tags.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Post|null
     */
    public function findBySlugWithCategoryAndTags($slug);

    /**
     * Get all posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     * Get all posts with it's category.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithCategory();

    /**
     * Get all posts with it's category and tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithCategoryAndTags();

    /**
     * Paginate all posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAll($postsPerPage);

    /**
     * Paginate all posts with it's category.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllWithCategory($postsPerPage);

    /**
     * Paginate all posts with it's category and tags.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllWithCategoryAndTags($postsPerPage);

    /**
     * Get all deleted posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeleted();

    /**
     * Get all deleted posts with it's category.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeletedWithCategory();

    /**
     * Get all deleted posts with it's category and tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllDeletedWithCategoryAndTags();

    /**
     * Paginate all deleted posts.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeleted($postsPerPage);

    /**
     * Paginate all deleted posts with it's category.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeletedWithCategory($postsPerPage);

    /**
     * Paginate all deleted posts with it's category and tags.
     *
     * @param int $postsPerPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function paginateAllDeletedWithCategoryAndTags($postsPerPage);
}
