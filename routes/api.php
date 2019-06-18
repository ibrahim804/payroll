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

Route::middleware('cors')->group(function(){

    Route::get('/', function(){
        return
        [
            [
                'status' => 'OK',
                'message' => 'Payroll version 1.0',
            ]
        ];
    });

    Route::post('login', 'API\UserController@login');
    Route::post('register', 'API\UserController@register');
    Route::get('user', 'API\UserController@user');
    Route::post('update/{id}', 'API\UserController@update');
    Route::post('change_password', 'API\UserController@change_password');
    Route::get('logout', 'API\UserController@logout');
    Route::get('delete_user/{id}', 'API\UserController@delete');
    Route::get('restore_user/{id}', 'API\UserController@restore');
    // Route::get('forgot_password', 'API\UserController@forgot_password');
    Route::get('users', 'API\UserController@index');


    Route::get('entry', 'AttendanceController@store');
    Route::get('exit', 'AttendanceController@update');
    Route::get('present/list/{month}/{day}', 'AttendanceController@index');
    Route::get('present/user/{month}/{id}', 'AttendanceController@show');


    Route::get('departments', 'DepartmentController@index');
    Route::get('departments/trashed', 'DepartmentController@trashedIndex');
    Route::get('department/{id}/users', 'DepartmentController@users');
    Route::post('department', 'DepartmentController@store');
    Route::get('department/{id}', 'DepartmentController@show');
    Route::post('department/{id}', 'DepartmentController@update');
    Route::get('department/delete/{id}', 'DepartmentController@destroy');
    Route::get('department/restore/{id}', 'DepartmentController@restore');

});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

















//
