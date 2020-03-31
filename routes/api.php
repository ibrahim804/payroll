<?php

use Illuminate\Http\Request;
// use DB;

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

Route::group(['middleware' => 'cors'], function(){

    Route::get('/', function(){
        return
        [
            [
                'status' => 'OK, simple get request',
                'message' => config('app.name'),
            ]
        ];
    });

    Route::post('/test_post', function(){
        return
        [
            [
                'status' => 'OK, simple post request',
                'value' => request('value'),
            ]
        ];
    });

    Route::get('database/{db}', function($db){
        return
        [
            [
                'status' => 'OK, here are all '.$db,
                $db => DB::select("select * from $db"),
            ]
        ];
    });


    Route::post('login', 'API\UserController@login');
    Route::post('register', 'API\UserController@register');
    Route::get('user-me',   'API\UserController@get_me');
    Route::get('user-dept-desg/{id}', 'API\UserController@user_dept_desg');
    Route::post('update-user', 'API\UserController@update');
    Route::get('logout', 'API\UserController@logout');
    Route::post('update-password', 'API\UserController@change_password');
    Route::post('forgot/password', 'API\UserController@forgot_password');
    Route::post('verify/verification-code', 'API\UserController@verifyVerificationCode');
    Route::post('set/new-password', 'API\UserController@setNewPasswordAfterUserVerification');
    Route::get('users', 'API\UserController@index');


    Route::get('roles', 'RoleController@index');
    Route::get('roles/leaders', 'RoleController@leaders');


    Route::post('salary', 'SalaryController@store');
    Route::get('salary/{user_id}', 'SalaryController@show');
    Route::get('salary-mine', 'SalaryController@showMySalary');
    Route::post('salary/{user_id}', 'SalaryController@update');


    Route::get('departments', 'DepartmentController@index');
    Route::get('department/{id}/designations', 'DepartmentController@designations');
    Route::get('department/{dept_id}/designation/{desgn_id}', 'DepartmentController@thisDeptDesgnUser');
    Route::post('department', 'DepartmentController@store');
    Route::post('department/{id}', 'DepartmentController@update');


    Route::get('designations', 'DesignationController@index');
    Route::post('designation', 'DesignationController@store');
    Route::post('designation/{id}', 'DesignationController@update');


    Route::get('leave-categories', 'LeaveCategoryController@index');
    Route::post('leave-category', 'LeaveCategoryController@store');


    Route::get('leave-count/user/{user_id}', 'LeaveCountController@store');           // CALLED FROM USER REGISTER METHOD
    Route::get('leave-count/leave-category/{leave_category_id}', 'LeaveCountController@createAfterNewLeaveCategoryCreation');     // CALLED FROM CATEGORY STORE
    Route::get ('leave-counts-of-user', 'LeaveCountController@employeeIndex');


    Route::get('leaves', 'LeaveController@index');
    Route::post('leave', 'LeaveController@store');
    Route::get('leavesOfAUser', 'LeaveController@show');
    Route::get('leave/available-duration/{leave_category_id}/{start_date}/{end_date}', 'LeaveController@showCountsDuration');
    Route::post('leave/approve/{id}', 'LeaveController@updateApprovalStatus');
    Route::get('leave/cancel/{id}', 'LeaveController@cancelLeave');
    Route::get('leave/remove/{id}', 'LeaveController@removeLeave');


    Route::post('working-day', 'WorkingDayController@store');


    Route::post('payment', 'PaymentController@store');
    Route::get('payments', 'PaymentController@index');
    Route::post('payment/send-payment-to-mail', 'PaymentController@sendPaymentToMail');
    Route::get('payment/generate-salary-sheet', 'PaymentController@getExportableData');


    Route::post('provident-fund', 'ProvidentFundController@store');


    Route::get('loan-requests', 'LoanRequestController@index');
    Route::post('loan-request', 'LoanRequestController@store');
    Route::post('loan-request/{id}', 'LoanRequestController@update');
    Route::get('loan-pending-request', 'LoanRequestController@show');
    Route::get('loan-request/loanable-amount', 'LoanRequestController@getLoanableAmountLimit');


    Route::post('loan-history', 'LoanHistoryController@store');
    Route::get('loan-histories', 'LoanHistoryController@index');
    Route::get('loan-history/user/latest', 'LoanHistoryController@getLatestHistoryOfEach');

});
















//
