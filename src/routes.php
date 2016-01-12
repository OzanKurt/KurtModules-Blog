<?php

Route::group([
    'prefix' => 'blog',
], function () {

    Route::get('welcome', function () {
        return 'Welcome!';
    });

});
