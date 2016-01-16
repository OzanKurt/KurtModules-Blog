<?php

namespace Kurt\Modules\Blog\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kurt\Modules\Blog\Observers\TagObserver;

/**
 * Kurt\Modules\Blog\Models\Tag.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Post[] $posts
 * @property-read \Kurt\Modules\Blog\Models\Post $postsCount
 * @property-read mixed $posts_count
 *
 * @method static \Illuminate\Database\Query\Builder|\Kurt\Modules\Blog\Models\Tag whereSlug($slug)
 */
class Tag extends BlogModel implements SluggableInterface
{
    use SluggableTrait;
    use SoftDeletes;

    /**
     * EloquentSluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name',
        'on_update'  => true,
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
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::observe(new TagObserver());
    }

    /**
     * Posts of the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blog_post_tag', 'tag_id', 'post_id');
    }

    /**
     * Posts count as hasOne relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function postsCount()
    {
        return $this->hasOne(Post::class)
            ->selectRaw('tag_id, count(*) as aggregate')
            ->groupBy('tag_id');
    }

    /**
     * Posts count of the category.
     *
     * @param $value
     *
     * @return int
     */
    public function getPostsCountAttribute($value)
    {
        return $this->getCountFromRelation('postsCount', $value);
    }

    /**
     * Latest post of the tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestPost()
    {
        return $this->posts()->latest()->first();
    }
}
