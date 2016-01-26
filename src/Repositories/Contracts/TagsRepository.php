<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

interface TagsRepository
{

    public function findById($id);

    public function findByIdWithCategory($id);

    public function findByIdWithPosts($id);

    public function findBySlug($slug);

    public function findBySlugWithPosts($slug);

    public function getAll();

    public function getAllWithCategory();

    public function paginateAll($postsPerPage);

    public function paginateAllWithCategory($postsPerPage);

}
