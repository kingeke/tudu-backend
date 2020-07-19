<?php

use Illuminate\Support\Facades\Route;

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
    return response()->json([
        'status'  => 'success',
        'message' => 'Welcome to our API, please use the api doc to get started',
        'api-doc' => 'https://documenter.getpostman.com/view/4827230/T1DjkzZN?version=latest',
    ]);
});
