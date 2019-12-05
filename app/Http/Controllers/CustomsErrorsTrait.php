<?php

namespace App\Http\Controllers;
use Validator;

trait CustomsErrorsTrait
{
	public function getErrorMessage($message)
    {
        return
        [
            [
                'status' => 'FAILED',
                'message' => $message,
            ]
        ];
    }

	/*
		Input: request array
		returns values of mentioned parameter after being validated by Laravel built in Validator
	*/

	public function validateUser($inputs)
	{
		return Validator::make($inputs, [
            'employee_id' => 'nullable|string',
            'full_name' => 'required|string|min:3|max:25',
            'user_name' => 'nullable|string|min:3|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            'date_of_birth' => 'nullable|string',
            'fathers_name' => 'nullable|string|min:3|max:25',
            'gender' => 'required|string',
            'marital_status' => 'nullable|string',
            'nationality' => 'nullable|string',
            'permanent_address' => 'nullable|string|min:10|max:300',
            'present_address' => 'nullable|string|min:10|max:300',
            'passport_number' => 'nullable|string',
            'phone' => 'required|string',
			'company_id' => 'nullable|string',
            'designation_id' => 'nullable|string',
            'department_id' => 'nullable|string',
			'working_day_id' => 'nullable|string',
            'joining_date' => 'required|string',
        ]);
	}
}
