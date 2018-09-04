<?php

Route::group([

    'middleware' => 'api'
], function () {

    Route::get('projects','APIController@getProjects');

    Route::post('photos','APIController@getPhotos');
    Route::post('videos','APIController@getVideos');
    Route::post('add-project','APIController@addProject');
    Route::post('delete-project','APIController@deleteProject');
    Route::post('edit-project','APIController@editProject');
    Route::post('project','APIController@getProject');

    Route::post('send','APIController@send');


    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('signup', 'AuthController@signup');

});
