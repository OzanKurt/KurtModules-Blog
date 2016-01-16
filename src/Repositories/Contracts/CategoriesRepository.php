<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

interface CategoriesRepository
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
     * Get all posts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll();

    /**
     * Get all categories with their posts counts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithPostsCounts();

    /**
     * Get all posts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrderByPopularity();
}
