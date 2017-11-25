# KurtModules-Blog

I tried to extract a simple, reusable blog module to use on my projects.
I used two external packages in total. For keeping the URL pretty on every possible page, I added [Eloquent Sluggable](https://github.com/cviebrock/eloquent-sluggable) and for displaying the content of a post I decided to use [Laravel Markdown](https://github.com/GrahamCampbell/Laravel-Markdown). This can of course be overwritten. 

The module includes the following models and each model has a default observer:

PS: Observers are currently not customizable. :(

#### Category

| Methods       | Description   |
| ------------- | ------------- |
| posts() | Posts of the category. (hasMany) |
| postsCount() | Posts count of the category. (hasOne) |
| latestPost() | Latest post of the category. (hasOne)      |
| scopePopular($descending = true) | Order the categories accoring to their popularities. (scope)      |

#### Tag

| Methods       | Description   |
| ------------- | ------------- |
| posts() | Posts of the tag. (belongsToMany) |
| postsCount() | Posts count of the tag. (hasOne) |
| latestPost() | Latest post of the tag. |

#### Post

Posts have a media type attribute so that the users can choose between a Text Post, Single Image Post, Multiple Image Post or Video Post. Videos support 3 different websites: YouTube, Vimeo, DailyMotion

| Methods       | Description   |
| ------------- | ------------- |
| category() | Category of the post. (belongsTo) |
| user() | User of the post. (belongsTo) |
| comments() | Comments of the post. (hasMany)      |
| commentsCount() | Comments count of the post. (hasOne) |
| latestComment() | Latest comment of the post. |
| tags() | Tags of the post. (belongsToMany) |
| tagsCount() | Tag count of the post. (hasOne) |
| scopePopular($descending = true) | Order the categories accoring to their popularities. (scope)      |
| scopeInCategory($categoryId = true) | Filter the posts to a category. (scope)* |
| scopeWithTags($tagIds = [], $and = false) | Filter the posts by their tags. (scope)* |

PS: * *This should be able to receive multiple ids sometime.*

#### Comment

| Methods       | Description   |
| ------------- | ------------- |
| post() | Post of the comment. (belongsTo) |
| user() | User of the comment. (belongsTo) |
| isApproved() | Check the approval state of the comment. |
| approve($state = true) | Update the appvoval of the comment. |
| disapprove() | Update the appvoval of the comment. |

### Contribution guidelines

Todo: Add contribution guidelines.

### Who do I talk to?

**Owner**: 

* Ozan Kurt (<ozankurt2@gmail.com>)
