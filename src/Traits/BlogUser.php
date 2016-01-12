<?php

namespace Kurt\Modules\Blog\Traits;

use Kurt\Modules\Blog\Models\Comment;
use Kurt\Modules\Blog\Models\Post;

/**
 * Gives the user class ability to use the methods related to blogging.
 *
 * @package Kurt\Modules\Blog\Traits
 * @property-read \Illuminate\Support\Collection blogPosts
 * @property-read int blogPostsCount
 * @property Post latestBlogPost
 * @property-read \Illuminate\Support\Collection blogComments
 * @property-read int blogCommentsCount
 * @property Comment latestBlogComment
 */
trait BlogUser
{

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
     * @return int
     */
    public function getBlogPostsCountAttribute()
    {
        if (!$this->relationLoaded('blogPostsCount')) {
            $this->load('blogPostsCount');
        }

        $related = $this->getRelation('blogPostsCount');

        return ($related) ? (int)$related->aggregate : 0;
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
     * @return int
     */
    public function getBlogCommentsCountAttribute()
    {
        if (!$this->relationLoaded('blogCommentsCount')) {
            $this->load('blogCommentsCount');
        }

        $related = $this->getRelation('blogCommentsCount');

        return ($related) ? (int)$related->aggregate : 0;
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
