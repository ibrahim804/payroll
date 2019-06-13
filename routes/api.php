<?php

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

Route::get('/', function(){
    return 'HRMS version 1.0';
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::get('user', 'API\UserController@user');
Route::post('update/{id}', 'API\UserController@update');
Route::post('change_password', 'API\UserController@change_password');
Route::get('logout', 'API\UserController@logout');
Route::get('delete_user/{id}', 'API\UserController@delete');
Route::get('restore_user/{id}', 'API\UserController@restore');
// Route::get('forgot_password', 'API\UserController@forgot_password');
