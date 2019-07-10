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

	public function validateUser($inputs)
	{
		return Validator::make($inputs, [
            'employee_id' => 'string',
            'full_name' => 'required|string|min:3|max:25',
            'user_name' => 'string|min:3|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            'date_of_birth' => 'date',
            'fathers_name' => 'string|min:3|max:25',
            'gender' => 'required|string',
            'marital_status' => 'string',
            'nationality' => 'string',
            'permanent_address' => 'string|min:10|max:300',
            'present_address' => 'string|min:10|max:300',
            'passport_number' => 'string',
            'phone' => 'required|string',
			'company_id' => 'string',
            'designation_id' => 'string',
            'department_id' => 'string',
            'joining_date' => 'required|date',
        ]);
	}
}
