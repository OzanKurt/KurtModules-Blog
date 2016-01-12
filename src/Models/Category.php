<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Kurt\Modules\Blog\Observers\CategoryObserverTrait;
use Kurt\Modules\Blog\Traits\CountFromRelationTrait;
use Kurt\Modules\Blog\Traits\SluggableTrait;

/**
 * Class Category
 * @package Kurt\Modules\Blog\Models
 * @property int id
 * @property string name
 * @property string slug
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property int postCount
 */
class Category extends Model implements SluggableInterface
{
    use CountFromRelationTrait;
    use CategoryObserverTrait;
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
    protected $table = 'blog_categories';

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

    /**
     * [posts description]
     * @return [type] [description]
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    /**
     * [postsCount description]
     * @return [type] [description]
     */
    public function postsCount()
    {
        return $this->hasOne(Post::class)
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id');
    }

    /**
     * [getPostsCountAttribute description]
     * @return [type] [description]
     */
    public function getPostsCountAttribute()
    {
        return $this->getCountFromRelation('postsCount');
    }

    /**
     * [latestPost description]
     * @return [type] [description]
     */
    public function latestPost()
    {
        return $this->hasOne(Post::class, 'category_id', 'id')->latest();
    }
}
