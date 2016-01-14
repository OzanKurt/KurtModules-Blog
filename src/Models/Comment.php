<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Kurt\Modules\Blog\Observers\CommentObserver;

/**
 * Kurt\Modules\Blog\Models\Comment
 *
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $post_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Kurt\Modules\Blog\Models\Post $posts
 * @property-read \App\User $user
 */
class Comment extends BlogModel
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blog_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'user_id',
        'post_id',
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

        Comment::observe(new CommentObserver());
    }

    /**
     * Post of the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    /**
     * User that created this comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            $this->getUserModelClass(),
            'user_id',
            $this->getUserModelPrimaryKey()
        );
    }
}
