<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'status'  => 'success',
        'message' => 'Welcome to our API, please use the api doc to get started',
        'api-doc' => 'https://documenter.getpostman.com/view/4827230/T1DjkzZN?version=latest',
    ]);
});

Route::group(['prefix' => 'auth'], function () {

    //sign up
    Route::post('/register', 'AuthController@register')->name('auth.register');

    //login
    Route::post('/login', 'AuthController@login')->name('auth.login');

    //log out
    Route::post('/logout', 'AuthController@logOut')->name('auth.logout')->middleware('auth:api');

});

Route::group(['middleware' => 'auth:api'], function () {

    Route::resource('todos', 'TodoController');

    Route::post('todos/{todo}/complete', 'TodoController@complete')->name('todos.complete');

    //profile routes
    Route::group(['prefix' => 'profile'], function () {

        //view logged in user profile
        Route::get('/', 'ProfileController@show')->name('profile.show');

        //update user profile
        Route::put('/update', 'ProfileController@updateProfile')->name('profile.update');

        //change user password
        Route::put('/change-password', 'ProfileController@changePassword')->name('profile.changePassword');

    });
});
