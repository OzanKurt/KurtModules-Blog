<?php

namespace Kurt\Modules\Blog\Repositories\Categories;

use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;

class EloquentCategoriesRepository implements CategoriesRepository
{

    /**
     * Model instance.
     *
     * @var Category
     */
    protected $model;

    /**
     * EloquentCategoriesRepository constructor.
     *
     * @param Category $model
     */
    function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     * @return Category
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a row by it's slug.
     *
     * @param $slug
     * @return Category
     */
    public function findBySlug($slug)
    {
        return $this->model->where('slug', '=', $slug)->first();
    }

    /**
     * Get all categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get all categories with their posts counts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithPostsCounts()
    {
        return $this->model->with(['postsCount'])->get();
    }

    /**
     * Get all categories ordered by their popularity.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrderByPopularity()
    {
        // Todo: Get all categories ordered by their popularity.
    }
}
