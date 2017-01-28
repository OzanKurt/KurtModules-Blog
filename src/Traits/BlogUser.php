<?php

namespace Kurt\Modules\Blog\Traits;

use Kurt\Modules\Blog\Models\Comment;
use Kurt\Modules\Blog\Models\Post;

use Kurt\Modules\Core\Traits\GetCountFromRelation;

/**
 * Gives the user class ability to use the methods related to blogging.
 *
 * @property-read \Illuminate\Support\Collection $blogPosts
 * @property-read int                            $blogPostsCount
 * @property Post                                $latestBlogPost
 * @property-read \Illuminate\Support\Collection $blogComments
 * @property-read int                            $blogCommentsCount
 * @property Comment                             $latestBlogComment
 */
trait BlogUser
{
    use GetCountFromRelation;

    /**
     * Posts of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogPosts()
    {
        return $this->hasMany(Post::class, 'user_id', $this->getKeyName());
    }

    /**
     * Users post count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function blogPostsCount()
    {
        return $this->hasOne(Post::class)
            ->selectRaw('user_id, count(*) as aggregate')
            ->groupBy('user_id');
    }

    /**
     * Get `blogPostsCount` attribute for easy access to posts count.
     *
     * @param $value
     *
     * @return int
     */
    public function getBlogPostsCountAttribute($value)
    {
        return $this->getCountFromRelation('blogPostsCount', $value);
    }

    /**
     * Get the latest post of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestBlogPost()
    {
        return $this->hasOne(Post::class, 'user_id', $this->getKeyName())->latest();
    }

    /**
     * Comments of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogComments()
    {
        return $this->hasMany(Comment::class, 'user_id', $this->getKeyName());
    }

    /**
     * Users comment count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function blogCommentsCount()
    {
        return $this->hasOne(Comment::class)
            ->selectRaw('user_id, count(*) as aggregate')
            ->groupBy('user_id');
    }

    /**
     * Get `blogCommentsCount` attribute for easy access to comments count.
     *
     * @param $value
     *
     * @return int
     */
    public function getBlogCommentsCountAttribute($value)
    {
        return $this->getCountFromRelation('blogCommentsCount', $value);
    }

    /**
     * Get the latest comment of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestBlogComment()
    {
        return $this->hasOne(Comment::class, 'user_id', $this->getKeyName())->latest();
    }
}
