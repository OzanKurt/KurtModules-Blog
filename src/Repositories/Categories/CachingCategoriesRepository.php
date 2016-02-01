<?php

namespace Kurt\Modules\Blog\Repositories\Categories;

use Illuminate\Contracts\Cache\Repository;
use Kurt\Modules\Blog\Repositories\Contracts\CategoriesRepositoryInterface;

class CachingCategoriesRepository implements CategoriesRepositoryInterface
{
    /**
     * Cache duration.
     *
     * @var int
     */
    protected $minutes = 15;

    /**
     * Cache instance.
     *
     * @var Store
     */
    protected $cache;

    /**
     * EloquentCategoriesRepository instance.
     *
     * @var \Kurt\Modules\Blog\Repositories\Categories\EloquentCategoriesRepository
     */
    protected $eloquentCategoriesRepository;

    /**
     * CachingCategoriesRepository constructor.
     *
     * @param Repository                   $cache
     * @param EloquentCategoriesRepository $eloquentCategoriesRepository
     */
    public function __construct(
        Repository $cache,
        EloquentCategoriesRepository $eloquentCategoriesRepository
    ) {
        $this->cache = $cache;
        $this->eloquentCategoriesRepository = $eloquentCategoriesRepository;
    }

    /**
     * Find a row by it's id.
     *
     * @param $id
     *
     * @return \Kurt\Modules\Blog\Models\Category
     */
    public function findById($id)
    {
        return $this->cache->tags([
            __METHOD__,
            __CLASS__,
        ])->remember(__METHOD__, $this->minutes, function () use ($id) {
            return $this->eloquentCategoriesRepository->findById($id);
        });
    }

    /**
     * Find a row by it's slug.
     *
     * @param $slug
     *
     * @return \Kurt\Modules\Blog\Models\Category
     */
    public function findBySlug($slug)
    {
        return $this->cache->tags([
            __METHOD__,
            __CLASS__,
        ])->remember(__METHOD__, $this->minutes, function () use ($slug) {
            return $this->eloquentCategoriesRepository->findBySlug($slug);
        });
    }

    /**
     * Get all categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return $this->cache->tags([
            __METHOD__,
            __CLASS__,
        ])->remember(__METHOD__, $this->minutes, function () {
            return $this->eloquentCategoriesRepository->getAll();
        });
    }

    /**
     * Get all categories with their posts counts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllWithPostsCounts()
    {
        return $this->cache->tags([
            __METHOD__,
            __CLASS__,
        ])->remember(__METHOD__, $this->minutes, function () {
            return $this->eloquentCategoriesRepository->getAllWithPostsCounts();
        });
    }

    /**
     * Get all categories ordering by popularity.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrderByPopularity()
    {
        return $this->cache->tags([
            __METHOD__,
            __CLASS__,
        ])->remember(__METHOD__, $this->minutes, function () {
            return $this->eloquentCategoriesRepository->getAllOrderByPopularity();
        });
    }
}
