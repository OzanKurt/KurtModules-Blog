<?php

Route::group([
    'prefix' => 'blog',
    'middleware' => ['web'],
], function () {

    Route::get('welcome', function () {
        return 'Welcome!';
    });

});
