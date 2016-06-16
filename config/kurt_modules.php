<?php

return [

    /**
     * Enables debugging if set to `true`.
     */
    'debug' => true,

    /**
     * User model class.
     */
    'user_model' => App\User::class,

    /**
     * Blog module related configuration.
     */
    'blog' => [

        /**
         * Enables caching if set to `true`.
         */
        'cache' => false,

        /**
         * How long should the caches last.
         */
        'cache_duration' => 15,

        /**
         * The file that covers the blog routes of your application.
         */
        'routes_path' => app_path('Http/blogRoutes.php'),

        /**
         * Should comments be approved by default while saving to database.
         */
        'preapproved_comments' => true,

    ],

    /**
     * Forum module related configuration.
     */
    'forum' => [

        /**
         * Enables caching if set to `true`.
         */
        'cache' => false,

        /**
         * How long should the caches last.
         */
        'cache_duration' => 15,

        /**
         * Forum routes path.
         */
        'routes_path' => app_path('Http/forumRoutes.php'),

    ],

];
