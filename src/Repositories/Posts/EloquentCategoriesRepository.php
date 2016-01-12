<?php

namespace Kurt\Modules\Blog\Repositories\Posts;

use Kurt\Modules\Blog\Models\Category;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepository;

class EloquentCategoriesRepository implements CategoriesRepository
{

    /**
     * Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    function __construct(Category $model)
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
     * Find a row by it's slug.
     *
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Model
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
     * Get all posts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrderByPopularity()
    {
        return $this->model->leftJoin('blog_posts', 'blog_posts.category_id', '=', 'blog_categories.id')
            ->get();
    }
}
