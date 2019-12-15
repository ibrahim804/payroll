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

Route::group(['middleware' => 'cors'] ,function(){

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
    Route::post('exists/email', 'API\UserController@exists_email');
    Route::get('user/{id}', 'API\UserController@user');
    Route::get('user-me',   'API\UserController@get_me');
    Route::get('user-dept-desg/{id}', 'API\UserController@user_dept_desg');
    Route::post('update/{id}', 'API\UserController@update');
    Route::get('logout', 'API\UserController@logout');
    Route::get('delete/user/{id}', 'API\UserController@delete');
    Route::get('delete/photo/user/{id}', 'API\UserController@remove_photo');
    Route::get('restore/user/{id}', 'API\UserController@restore');
    Route::post('update-password', 'API\UserController@change_password');
    Route::post('forgot/password', 'API\UserController@forgot_password');
    Route::post('verify/verification-code', 'API\UserController@verifyVerificationCode');
    Route::post('set/new-password', 'API\UserController@setNewPasswordAfterUserVerification');
    Route::get('users', 'API\UserController@index');


    Route::get('companies', 'CompanyController@index');
    Route::get('companies/trashed', 'CompanyController@trashedIndex');
    Route::post('company', 'CompanyController@store');
    Route::get('company/{id}', 'CompanyController@show');
    Route::post('company/{id}', 'CompanyController@update');
    Route::get('company/delete/{id}', 'CompanyController@destroy');
    Route::get('company/restore/{id}', 'CompanyController@restore');


    Route::get('salaries', 'SalaryController@index');
    Route::post('salary', 'SalaryController@store');
    Route::get('salary/{user_id}', 'SalaryController@show');
    Route::post('salary/{user_id}', 'SalaryController@update');


    Route::get('entry', 'AttendanceController@store');
    Route::get('exit', 'AttendanceController@update');
    Route::get('present/list/{month}/{day}', 'AttendanceController@index');
    Route::get('present/user/{month}/{id}', 'AttendanceController@show');


    Route::get('departments', 'DepartmentController@index');
    Route::get('departments/trashed', 'DepartmentController@trashedIndex');
    Route::get('department/{id}/users', 'DepartmentController@users');
    Route::get('department/{id}/designations', 'DepartmentController@designations');
    Route::get('department/{dept_id}/designation/{desgn_id}', 'DepartmentController@thisDeptDesgnUser');
    Route::post('department', 'DepartmentController@store');
    Route::get('department/{id}', 'DepartmentController@show');
    Route::post('department/{id}', 'DepartmentController@update');
    Route::get('department/delete/{id}', 'DepartmentController@destroy');
    Route::get('department/restore/{id}', 'DepartmentController@restore');


    Route::get('designations', 'DesignationController@index');
    Route::get('designations/trashed', 'DesignationController@trashedIndex');
    Route::post('designation', 'DesignationController@store');
    Route::get('designation/{id}', 'DesignationController@show');
    Route::post('designation/{id}', 'DesignationController@update');
    Route::get('designation/delete/{id}', 'DesignationController@destroy');
    Route::get('designation/restore/{id}', 'DesignationController@restore');


    Route::get('leave-categories', 'LeaveCategoryController@index');
    Route::get('leave-categories/trashed', 'LeaveCategoryController@trashedIndex');
    Route::post('leave-category', 'LeaveCategoryController@store');
    Route::get('leave-category/{id}', 'LeaveCategoryController@show');
    Route::post('leave-category/{id}', 'LeaveCategoryController@update');
    Route::get('leave-category/delete/{id}', 'LeaveCategoryController@destroy');
    Route::get('leave-category/restore/{id}', 'LeaveCategoryController@restore');


    Route::get('leave-counts', 'LeaveCountController@index');
    Route::post('leave-count', 'LeaveCountController@store');
    Route::get('leave-count/{user_id}/{leave_category_id}', 'LeaveCountController@show');
    Route::post('leave-count/{id}', 'LeaveCountController@update');


    Route::get('leaves', 'LeaveController@index');
    Route::post('leave', 'LeaveController@store');
    Route::get('leaves/{user_id}', 'LeaveController@show');
    Route::get('leave/available-duration/{leave_category_id}/{start_date}/{end_date}', 'LeaveController@showCountsDuration');
    Route::post('leave/{id}', 'LeaveController@update');
    Route::post('leave/approve/{id}', 'LeaveController@updateApprovalStatus');
    Route::get('leave/cancel/{id}', 'LeaveController@cancelLeave');


    Route::post('working-day', 'WorkingDayController@store');
    Route::get('working-day/{user_id}', 'WorkingDayController@show');
    Route::post('working-day/{user_id}', 'WorkingDayController@update');


    Route::post('payment', 'PaymentController@store');
    Route::get('payments', 'PaymentController@index');
    Route::get('payment/send-payment-to-mail/{user_id}', 'PaymentController@sendPaymentToMail');


    Route::post('provident-fund', 'ProvidentFundController@store');
    Route::get('provident-fund', 'ProvidentFundController@show');


    Route::get('loan-requests', 'LoanRequestController@index');
    Route::post('loan-request', 'LoanRequestController@store');
    Route::post('loan-request/{id}', 'LoanRequestController@update');
    Route::get('loan-pending-request', 'LoanRequestController@show');


    Route::get('loan-history/pay/eligibility', 'LoanHistoryController@checkEligibility'); // check user
    Route::post('loan-history', 'LoanHistoryController@store');     // pay back loan request
    Route::get('loan-pay-backs', 'LoanHistoryController@getAllPendingPayBacks'); // pending pay backs request
    Route::get('loan-pay-back-accept/{id}', 'LoanHistoryController@acceptLoanPayBackRequest'); // accept pay back request
    Route::get('loan-histories', 'LoanHistoryController@index');    // all transaction, user


    Route::post('file-upload/create/user', 'FileController@create_user'); // must work with xcel, not csv
    Route::post('upload/user/profile-picture', 'FileController@setProfilePicture');
    Route::get('get-profile-picture', 'FileController@getProfilePicture');

});
















//
