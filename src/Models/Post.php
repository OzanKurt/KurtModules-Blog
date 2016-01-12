<?php

namespace Kurt\Modules\Blog\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kurt\Modules\Blog\Observers\PostObserverTrait;
use Kurt\Modules\Blog\Traits\CountFromRelationTrait;
use Kurt\Modules\Blog\Traits\SluggableTrait;

/**
 * Class Post
 * @package Kurt\Modules\Blog\Models
 * @property int id
 * @property string title
 * @property string slug
 * @property string content
 * @property int user_id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property Category category
 * @property \Illuminate\Contracts\Auth\Authenticatable user
 * @property-read \Illuminate\Support\Collection comments
 * @property-read int blogCommentsCount
 * @property Comment latestBlogComment
 * @property-read \Illuminate\Support\Collection tags
 * @property-read int tagsCount
 */
class Post extends Model implements SluggableInterface
{
    use CountFromRelationTrait;
    use PostObserverTrait;
    use SluggableTrait;
    use SoftDeletes;

    /**
     * EloquentSluggable configuration
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'user_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $userModelClass = config('auth.providers.users.model');
        $userModel = app($userModelClass);
        return $this->belongsTo($userModelClass, 'user_id', $userModel->getKey());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commentsCount()
    {
        return $this->hasOne(Comment::class)
            ->selectRaw('post_id, count(*) as aggregate')
            ->groupBy('post_id');
    }

    /**
     * @return int
     */
    public function getCommentsCountAttribute()
    {
        return $this->getCountFromRelation('commentsCount');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestComment()
    {
        return $this->hasOne(Comment::class, 'post_id', 'id')->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'tag_id', 'post_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tagsCount()
    {
        return $this->hasOne(Tag::class)
            ->selectRaw('post_id, count(*) as aggregate')
            ->groupBy('post_id');
    }

    /**
     * @return int
     */
    public function getTagsCountAttribute()
    {
        return $this->getCountFromRelation('tagsCount');
    }
}
