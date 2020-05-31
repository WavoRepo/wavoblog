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

    Route::group(['namespace' => 'Api'], function ()
    {
        /*** BOF POSTS ***/
        Route::get('/post/published', 'PostController@published');
        Route::get('/post/{post}', 'PostController@find');

        Route::group(['middleware' => 'auth:api'], function ()
        {
            /*** BOF DASHBOARD ***/
            Route::get('/get-dashboard-services', 'DashboardController@getServices');

            /*** BOF USER ***/
            Route::get('/user', 'UserController@index');
            Route::get('/user/active', 'UserController@active');
            Route::get('/user/{user}', 'UserController@show');
            Route::post('/user/{user}', 'UserController@update');
            Route::delete('/user/{user}', 'UserController@destroy');

            /*** BOF SECURITY ***/
            Route::post('/security/password/confirm', 'UserController@confirm');

            /*** BOF POSTS ***/
            Route::get('/post', 'PostController@collection');
            Route::post('/post', 'PostController@store');
            Route::post('/post/{post}', 'PostController@update');
            Route::delete('/post/{post}', 'PostController@destroy');
        });
    });

    /*** REQUEST METHOD DON'T MATCH ***/
    Route::fallback(function () {
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact support@' . strtolower(config('app.name')) . '.com'
        ], 404);
    });
});
