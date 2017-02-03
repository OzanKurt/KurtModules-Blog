<?php

namespace Kurt\Modules\Blog\Models;

use Cviebrock\EloquentSluggable\Sluggable;

use Kurt\Modules\Blog\Observers\CategoryObserver;

use Kurt\Modules\Core\Traits\GetCountFromRelation;
use Kurt\Modules\Core\Traits\GetUserModelData;

/**
 * Class Category
 *
 * @package Kurt\Modules\Blog\Models
 * @property integer                                                                        $id
 * @property string                                                                         $name
 * @property string                                                                         $slug
 * @property \Carbon\Carbon                                                                 $created_at
 * @property \Carbon\Carbon                                                                 $updated_at
 * @property \Carbon\Carbon                                                                 $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Kurt\Modules\Blog\Models\Post[] $posts
 * @property-read \Kurt\Modules\Blog\Models\Post                                            $postsCount
 * @property-read mixed                                                                     $posts_count
 * @property-read \Kurt\Modules\Blog\Models\Post                                            $latestPost
 * @method static \Illuminate\Database\Query\Builder|\Kurt\Modules\Blog\Models\Category whereSlug($slug)
 */
class Category extends Model implements SluggableInterface
{
    use GetCountFromRelation;
    use GetUserModelData;
    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }

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

        self::observe(new CategoryObserver());
    }

    /**
     * Posts of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany($this->getModel('post'), 'category_id', 'id');
    }

    /**
     * Posts count as hasOne relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function postsCount()
    {
        return $this->hasOne($this->getModel('post'))
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id');
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
     * Latest post of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestPost()
    {
        return $this->hasOne($this->getModel('post'), 'category_id', 'id')->latest()->limit(1);
    }

    public function scopePopular($query, $descending = true)
    {
        $query->selectRaw('blog_categories.*, count(`blog_posts`.`id`) as postsCount')
            ->leftJoin('blog_posts', 'blog_posts.category_id', '=', 'blog_categories.id')
            ->groupBy('blog_categories.id')
            ->orderBy('postsCount', $descending ? 'desc' : 'asc');
    }
}
