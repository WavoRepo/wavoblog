<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('homepage');
});

Auth::routes();

Route::get('/blog/{slug1?}/{slug2?}', 'PostController@index')->name('blog');

Route::get('/admin/{slug1?}/{slug2?}', 'AdminController@show')->name('index');
