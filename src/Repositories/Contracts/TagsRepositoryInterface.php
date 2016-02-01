<?php

namespace Kurt\Modules\Blog\Repositories\Contracts;

interface TagsRepositoryInterface
{

    public function findById($id);

    public function findByIdWithPosts($id);

    public function findBySlug($slug);

    public function findBySlugWithPosts($slug);

    public function getAll();

    public function getAllWithPosts();

    public function paginateAll($postsPerPage);

    public function paginateAllWithPosts($postsPerPage);

}
