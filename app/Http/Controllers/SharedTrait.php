<?php

namespace App\Http\Controllers;
use Validator;
use App\Salary;

trait SharedTrait
{
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
            'deposit_pf' => 'nullable|string',
        ]);
	}

    public function calculatePayableAmount(Salary $salary)
    {
        $amounts = [];

        $amounts['gross_salary'] = $salary['basic_salary']
                                 + $salary['house_rent_allowance'] + $salary['medical_allowance']
                                 + $salary['special_allowance'] + $salary['fuel_allowance']
                                 + $salary['phone_bill_allowance'] + $salary['other_allowance'];

        $amounts['total_deduction'] = $salary['tax_deduction'] + $salary['provident_fund'] + $salary['other_deduction'];
        $amounts['net_salary'] = $amounts['gross_salary'] - $amounts['total_deduction'];

        return $amounts;
    }

    public function calculateGross($attributes)
    {
        return $attributes['basic_salary']
             + $attributes['house_rent_allowance'] + $attributes['medical_allowance']
             + $attributes['fuel_allowance'] + $attributes['phone_bill_allowance']
             + $attributes['special_allowance'] + $attributes['other_allowance'];
    }

    public function calculatePayableAmountAfterLeaveDeduction($unpaidCount, $grossSalary, $netSalary) {
        return $netSalary - $this->calculateLeaveDeduction($unpaidCount, $grossSalary);
    }

    public function calculateLeaveDeduction($unpaidCount, $grossSalary) {
        return ($grossSalary / 30) * $unpaidCount;
    }
}








//
