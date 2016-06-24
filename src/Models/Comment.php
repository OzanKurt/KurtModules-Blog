<?php

namespace Kurt\Modules\Blog\Models;

use Kurt\Modules\Blog\Observers\CommentObserver;

use Kurt\Modules\Core\Traits\GetCountFromRelation;
use Kurt\Modules\Core\Traits\GetUserModelData;

/**
 * Class Comment
 *
 * @package Kurt\Modules\Blog\Models
 * @property integer                             $id
 * @property string                              $content
 * @property integer                             $user_id
 * @property integer                             $post_id
 * @property boolean                             $approved
 * @property \Carbon\Carbon                      $created_at
 * @property \Carbon\Carbon                      $updated_at
 * @property \Carbon\Carbon                      $deleted_at
 * @property-read \Kurt\Modules\Blog\Models\Post $post
 * @property-read \App\User                      $user
 */
class Comment extends Model
{
    use GetCountFromRelation;
    use GetUserModelData;

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
        'approved',
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
     * Casts columns to requested types.
     * 
     * @var array
     */
    protected $casts = [
        'approved' => 'boolean',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::observe(new CommentObserver());
    }

    /**
     * Overwrite parents create to set a default approval state.
     * 
     * @param static
     */
    public static function create(array $attributes = [])
    {
        if (!array_key_exists('approved', $attributes)) {
            $attributes['approved'] = config('kurt_modules.blog.preapproved_comments');
        }

        return parent::create($attributes);
    }

    /**
     * Post of the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo($this->getModel('post'), 'post_id', 'id');
    }

    /**
     * User that created this comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            $this->getUserModelClassName(),
            'user_id',
            $this->getUserModelPrimaryKey()
        );
    }

    /**
     * Get is the comment is approved.
     * 
     * @return boolean
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * Update the comment as approved in the database.
     * 
     * @return $this
     */
    public function approve($state = true)
    {
        $this->approved == $state;

        $this->save();

        return $this;
    }

    /**
     * Update the comment as disapproved in the database.
     * 
     * @return $this
     */
    public function disapprove()
    {
        return $this->approve(false);
    }
}
