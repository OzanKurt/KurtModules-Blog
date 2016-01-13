<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

interface TagsRepository
{

    /**
     * Find a row by it's id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById($id);

    /**
     * Find a row by it's id.
     *
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlug($slug);

    /**
     * Find a row by it's id with it's posts.
     *
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlugWithPosts($slug);

    /**
     * Find a row by it's id with it's category.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByIdWithCategory($id);

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
     * Paginate all posts.
     *
     * @param integer $postsPerPage
     * @return \Illuminate\Support\Collection
     */
    public function paginateAll($postsPerPage);

    /**
     * Paginate all posts with it's category.
     *
     * @param integer $postsPerPage
     * @return \Illuminate\Support\Collection
     */
    public function paginateAllWithCategory($postsPerPage);

}
