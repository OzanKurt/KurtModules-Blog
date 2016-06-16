<?php

namespace Kurt\Modules\Blog\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Kurt\Modules\Blog\Observers\PostObserver;

use Kurt\Modules\Core\Traits\GetCountFromRelation;
use Kurt\Modules\Core\Traits\GetUserModelData;

/**
 * Class Post
 *
 * @package Kurt\Modules\Blog\Models
 * @property integer                                                                           $id
 * @property string                                                                            $title
 * @property string                                                                            $slug
 * @property string                                                                            $media
 * @property string                                                                            $content
 * @property integer                                                                           $type
 * @property integer                                                                           $user_id
 * @property integer                                                                           $category_id
 * @property \Carbon\Carbon                                                                    $created_at
 * @property \Carbon\Carbon                                                                    $updated_at
 * @property \Carbon\Carbon                                                                    $deleted_at
 * @property-read \Kurt\Modules\Blog\Models\Category                                           $category
 * @property-read \App\User                                                                    $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Comment[] $comments
 * @property-read \Kurt\Modules\Blog\Models\Comment                                            $commentsCount
 * @property-read mixed                                                                        $comments_count
 * @property-read \Kurt\Modules\Blog\Models\Comment                                            $latestComment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Tag[]     $tags
 * @property-read \Kurt\Modules\Blog\Models\Tag                                                $tagsCount
 * @property-read mixed                                                                        $tags_count
 * @method static \Illuminate\Database\Query\Builder|\Kurt\Modules\Blog\Models\Post whereSlug($slug)
 */
class Post extends Model implements SluggableInterface
{
    use GetCountFromRelation;
    use GetUserModelData;
    use SluggableTrait;
    use SoftDeletes;

    /**
     * EloquentSluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title',
        'on_update'  => true,
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
        'type',
        'media',
        'content',
        'view_count',
        'last_viewer_ip',
        'user_id',
        'published_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
        'deleted_at',
    ];

    /**
     * Post types as an array.
     *
     * @var array
     */
    public static $types = [
        0 => Post::TYPE_TEXT,
        1 => Post::TYPE_IMAGE,
        2 => POST::TYPE_VIDEO,
        3 => POST::TYPE_CAROUSEL,
    ];

    /**
     * The `media` column will be null.
     */
    const TYPE_TEXT = 0;

    /**
     * The `media` column will be a `string`.
     */
    const TYPE_IMAGE = 1;

    /**
     * The `media` column will be a `string`.
     */
    const TYPE_VIDEO = 2;

    /**
     * The `media` column will be a `string[]`.
     */
    const TYPE_CAROUSEL = 3;

    /**
     * Video types as an array.
     *
     * @var array
     */
    public static $videoTypes = [
        0 => Post::VIDEO_TYPE_YOUTUBE,
        1 => Post::VIDEO_TYPE_VIMEO,
        2 => Post::VIDEO_TYPE_DAILYMOTION,
    ];

    /**
     * The `videoType` attribute.
     */
    const VIDEO_TYPE_YOUTUBE = 0;
    const VIDEO_TYPE_VIMEO = 1;
    const VIDEO_TYPE_DAILYMOTION = 2;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::observe(new PostObserver());
    }

    /**
     * Get category belongs to relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * User that created the post.
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
     * Get comments has many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * Get comments count has one relation.
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
     * Get comments count attribute.
     *
     * @param $value
     *
     * @return int
     */
    public function getCommentsCountAttribute($value)
    {
        return $this->getCountFromRelation('commentsCount', $value);
    }

    /**
     * Get latest comment has one relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestComment()
    {
        return $this->hasOne(Comment::class, 'post_id', 'id')->latest();
    }

    /**
     * Get tags belongs to many relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag', 'post_id', 'tag_id');
    }

    /**
     * Get tags count has one relation.
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
     * Get tags count attribute.
     *
     * @param int $value
     *
     * @return int
     */
    public function getTagsCountAttribute($value)
    {
        return $this->getCountFromRelation('tagsCount', $value);
    }

    /**
     * Popularity by view count.
     * 
     * @param  [type]  $query      [description]
     * @param  boolean $descending [description]
     * 
     * @return [type]              [description]
     */
    public function scopePopular($query, $descending = true)
    {
        $query->orderBy('view_count', $descending ? 'desc' : 'asc');
    }

    /**
     * Determine if the posts media type is equal to the given type.
     * 
     * @param  int  $type One of the constants from this class.
     * 
     * @return boolean
     */
    public function isMediaType($type)
    {
        return $this->type == $type;
    }

    /**
     * Get the media value in a better and fitting type. 
     * 
     * @param  string $value
     * 
     * @return mixed
     */
    public function getMediaAttribute($value)
    {
        switch ($this->type) {
            case self::TYPE_TEXT:
                return null;
                break;
            case self::TYPE_IMAGE:
                return $value;
                break;
            case self::TYPE_VIDEO:
                return $value;
                break;
            case self::TYPE_CAROUSEL:
                return json_decode($value, true);
                break;
            default:
                throw new \Exception("Posts media type is invalid.");
                break;
        }
    }

    /**
     * Set the media value in a better and fitting type. 
     * 
     * @param  mixed $value
     */
    public function setMediaAttribute($value)
    {
        switch ($this->type) {
            case self::TYPE_TEXT:
                $result = null;
                break;
            case self::TYPE_IMAGE:
                $result = $this->media;
                break;
            case self::TYPE_VIDEO:
                $result = $this->media;
                break;
            case self::TYPE_CAROUSEL:
                $result = json_encode($this->media);
                break;
            default:
                throw new \Exception("Posts media type is invalid.");
                break;
        }

        $this->attributes['media'] = $result;
    }

    /**
     * Get the thumbnail image path of the post.
     * 
     * @return string|null
     */
    public function getThumbnailAttribute()
    {
        switch ($this->type) {
            case self::TYPE_TEXT:
                $result = null;
                break;
            case self::TYPE_IMAGE:
                $result = $this->media;
                break;
            case self::TYPE_VIDEO:
                $result = $this->getVideoThumbnail();
                break;
            case self::TYPE_CAROUSEL:
                $result = json_encode($this->media)[0];
                break;
            default:
                throw new \Exception("Invalid media type.");
                break;
        }

        return $result;
    }

    /**
     * Get the thumbnail of youtube video.
     * 
     * @return string|null Thumbnail URL.
     */
    public function getVideoTypeAttribute()
    {
        if ($this->type != self::TYPE_VIDEO) {
            throw new \Exception("This posts media type is not `Video`, cannot get `videoType`.");
        }
        
        if (getDailyMotionId($this->media)) {
            return self::VIDEO_TYPE_DAILYMOTION;
        } elseif (getVimeoId($this->media)) {
            return self::VIDEO_TYPE_VIMEO;
        } elseif (getYoutubeId($this->media)) {
            return self::VIDEO_TYPE_YOUTUBE;
        }
    }

    /**
     * Get the video id of the post.
     * 
     * @return string
     */
    private function getVideoIdAttribute()
    {
        switch ($this->videoType) {
            case self::VIDEO_TYPE_DAILYMOTION:
                return getDailyMotionId($this->media);
                break;
            case self::VIDEO_TYPE_VIMEO:
                return getVimeoId($this->media);
                break;
            case self::VIDEO_TYPE_YOUTUBE:
                return getYoutubeId($this->media);
                break;
            default:
                throw new \Exception("This posts video type is not valid.");
                break;
        }
    }

    /**
     * Get the video thumbnail of the post.
     * 
     * @return string
     */
    private function getVideoThumbnail()
    {
        $qualities = config('kurt_modules.blog.video_thumbnail_qualities');

        switch ($this->videoType) {
            case self::VIDEO_TYPE_DAILYMOTION:
                return 'http://www.dailymotion.com/thumbnail/video/' . $this->videoId;
                break;
            case self::VIDEO_TYPE_VIMEO:
                $result = file_get_contents('http://vimeo.com/api/v2/video/' . $this->videoId . '.php');

                $hash = unserialize($result);

                return $hash[0][$qualities['vimeo']];
                break;
            case self::VIDEO_TYPE_YOUTUBE:
                return 'http://img.youtube.com/vi/' . $this->videoId . '/' . $qualities['youtube'] . '.jpg';
                break;
            default:
                throw new \Exception("This posts video type is not valid.");
                break;
        }
    }

    /**
     * Get video location url.
     * 
     * @return string Url of the video.
     */
    public function getVideoLocationAttribute()
    {
        switch ($this->videoType) {
            case self::VIDEO_TYPE_DAILYMOTION:
                return 'http://www.dailymotion.com/embed/video/' . $this->videoId;
                break;
            case self::VIDEO_TYPE_VIMEO:
                return 'http://player.vimeo.com/video/' . $this->videoId;
                break;
            case self::VIDEO_TYPE_YOUTUBE:
                return 'http://www.youtube.com/embed/' . $this->videoId;
                break;
            default:
                throw new \Exception("This posts video type is not valid.");
                break;
        }
    }

    /**
     * Get the html to embed the video.
     * 
     * @return string
     */
    public function getVideoEmbedAttribute()
    {
        $html = "<style>
                .embed-container { 
                    position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } 
                .embed-container iframe, .embed-container object, .embed-container embed { 
                    position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
                </style>";

        $html .= "<div class='embed-container'>
                    <iframe src=" . $this->videoLocation . " frameborder='0' 
                        awebkitAllowFullScreen mozallowfullscreen llowfullscreen
                    ></iframe>
                </div>";

        return $html;
    }
}
