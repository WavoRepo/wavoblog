<?php

use App\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

    /*** BOF POSTS ***/
    Route::get('/frontend/post', 'PostsController@index');
    Route::get('/frontend/post/{id}', 'PostsController@show');

    Route::group(['middleware' => 'auth:api'], function () {

        /*** BOF DASHBOARD ***/
        Route::get('/get-dashboard-services', 'DashboardController@getServices');

        /*** BOF USER ***/
        Route::get('/user/active', 'UserController@active');
        Route::get('/user', 'UserController@index');
        Route::get('/user/{id}', 'UserController@show');
        Route::post('/user/{id}', 'UserController@update');

            /*** BOF SECURITY ***/
        Route::post('/security/password/confirm', 'UserController@confirm');

        /*** BOF POSTS ***/
        Route::get('/post', 'PostController@index');
        Route::post('/post', 'PostController@store');
        Route::get('/post/{id}', 'PostController@show');
        Route::post('/post/{id}', 'PostController@update');
        Route::delete('/post/{id}', 'PostController@destroy');
    });

    /*** REQUEST METHOD DON'T MATCH ***/
    Route::fallback(function () {
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact support@' . strtolower(config('app.name')) . '.com'
        ], 404);
    });
});
