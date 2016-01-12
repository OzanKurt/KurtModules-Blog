<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kurt\Modules\Blog\Observers\TagObserverTrait;
use Kurt\Modules\Blog\Traits\SluggableTrait;
use Kurt\Modules\Blog\Traits\CountFromRelationTrait;

class Tag extends Model implements SluggableInterface
{
    use CountFromRelationTrait;
    use TagObserverTrait;
    use SluggableTrait;
    use SoftDeletes;

    /**
     * EloquentSluggable configuration
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blog_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function postsCount()
    {
        return $this->hasOne(Post::class)
            ->selectRaw('tag_id, count(*) as aggregate')
            ->groupBy('tag_id');
    }

    public function getPostsCountAttribute()
    {
        return $this->getCountFromRelation('postsCount');
    }

    public function latestPost()
    {
        return $this->posts()->take(1)->latest();
    }
}
