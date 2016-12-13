<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{

    private $defaultModels = [
        'category' => Category::class,
        'comment' => Comment::class,
        'post' => Post::class,
        'tag' => Tag::class,
    ];

    protected function getModel($modelKey)
    {
        $configModels = config('kurt_modules.blog.custom_models');

        $customModelClass = $configModels[$modelKey];

        if (is_null($customModelClass)) {
            return $defaultModels[$modelKey];
        }

        return $customModelClass;
    }
}
