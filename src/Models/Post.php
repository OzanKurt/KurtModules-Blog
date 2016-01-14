<?php

namespace Kurt\Modules\Blog\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kurt\Modules\Blog\Observers\PostObserver;
use Kurt\Modules\Blog\Traits\CountFromRelationTrait;

/**
 * Kurt\Modules\Blog\Models\Post
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $user_id
 * @property integer $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Kurt\Modules\Blog\Models\Category $category
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Comment[] $comments
 * @property-read \Kurt\Modules\Blog\Models\Comment $commentsCount
 * @property-read mixed $comments_count
 * @property-read \Kurt\Modules\Blog\Models\Comment $latestComment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Tag[] $tags
 * @property-read \Kurt\Modules\Blog\Models\Tag $tagsCount
 * @property-read mixed $tags_count
 * @method static \Illuminate\Database\Query\Builder|\Kurt\Modules\Blog\Models\Post whereSlug($slug)
 */
class Post extends Model implements SluggableInterface
{
    use CountFromRelationTrait;
    use SluggableTrait;
    use SoftDeletes;

    /**
     * EloquentSluggable configuration
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title',
        'on_update' => true,
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
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        Post::observe(new PostObserver());
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $userModelClass = config('auth.providers.users.model');
        $userModel = app($userModelClass);
        return $this->belongsTo($userModelClass, 'user_id', $userModel->getKeyName());
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commentsCount()
    {
        return $this->hasOne(Comment::class)
            ->selectRaw('post_id, count(*) as aggregate')
            ->groupBy('post_id');
    }

    /**
     * Todo: Description.
     *
     * @return int
     */
    public function getCommentsCountAttribute()
    {
        return $this->getCountFromRelation('commentsCount');
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestComment()
    {
        return $this->hasOne(Comment::class, 'post_id', 'id')->latest();
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'post_id', 'tag_id');
    }

    /**
     * Todo: Description.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tagsCount()
    {
        return $this->hasOne(Tag::class)
            ->selectRaw('post_id, count(*) as aggregate')
            ->groupBy('post_id');
    }

    /**
     * Todo: Description.
     *
     * @return int
     */
    public function getTagsCountAttribute()
    {
        return $this->getCountFromRelation('tagsCount');
    }
}
