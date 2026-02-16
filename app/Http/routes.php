<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    'as' => 'auth.login', 
    'uses' => 'Auth\AuthController@loginIndex'
]);

Route::post('/login', 'Auth\AuthController@login');

Route::get('/register', 'Auth\AuthController@registerIndex');
Route::post('/register', 'Auth\AuthController@register');

Route::get('/logout', [
    'as' => 'auth.logout', 
    'uses' => 'Auth\AuthController@logout'
]);

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/', [
        'as' => 'movies.index', 
        'uses' => 'MoviesController@index'
    ]);

    Route::get('/movies', 'MoviesController@index');
    Route::get('/movies/live-search', 'MoviesController@liveSearch');
    Route::get('/movies/{id}', 'MoviesController@movieDetails');

    Route::post('/favorites', 'MoviesController@addToFavorites');
    Route::delete('/favorites/{id}', 'MoviesController@removeFromFavorites');
});