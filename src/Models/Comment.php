<?php

namespace Kurt\Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 * @package Kurt\Modules\Blog\Models
 * @property int id
 * @property string content
 * @property int user_id
 * @property int post_id
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 * @property \Carbon\Carbon deleted_at
 * @property-read \Illuminate\Support\Collection posts
 * @property-read \Illuminate\Contracts\Auth\Authenticatable user
 */
class Comment extends Model implements SluggableInterface 
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
    protected $dates = ['deleted_at'];

    public function posts()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
        $userModelClass = config('auth.providers.users.model');
        $userModel = app($userModelClass);
        return $this->belongsTo($userModelClass, 'user_id', $userModel->getKey());
    }
}
