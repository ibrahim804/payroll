<?php

namespace App\Http\Controllers;

use App\Salary;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Http\Controllers\SharedTrait;
use Validator;
use App\MyErrorObject;

class SalaryController extends Controller
{
    use CustomsErrorsTrait, SharedTrait;
    private $myObject;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
        $this->myObject = new MyErrorObject;
    }

    public function store(Request $request)
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $validate_attributes = $this->validateSalary();
        $user = User::find($validate_attributes['user_id']);

        $gross = $this->calculateGross($validate_attributes);
        $validate_attributes['provident_fund'] = (double)$gross * $this->myObject->monthly_deposit_rate * $user->deposit_pf;

        $salary = Salary::create($validate_attributes);
        $user->update(['salary_id' => $salary->id]);
        $calculated_amounts = $this->calculatePayableAmount($salary);

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
                'gross_salary' => $calculated_amounts['gross_salary'],
                'total_deduction' => $calculated_amounts['total_deduction'],
                'net_salary' => $calculated_amounts['net_salary'],
            ]
        ];
    }

    public function show($user_id)
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $user = User::find($user_id);

        if(! $user) return $this->getErrorMessage('User doesn\'t exist');

        return $this->getSalary($user);
    }

    public function showMySalary() {
        return $this->getSalary(auth()->user());
    }

    private function getSalary($user) {

        $salary = $user->salary;

        if(!$salary) return $this->getErrorMessage('This user doesn\'t have any salary yet.');

        $calculated_amounts = $this->calculatePayableAmount($salary);

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
                'gross_salary' => $calculated_amounts['gross_salary'],
                'total_deduction' => $calculated_amounts['total_deduction'],
                'net_salary' => $calculated_amounts['net_salary'],
            ]
        ];
    }

    public function update(Request $request, $user_id)
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

        $salary = User::findOrFail($user_id)->salary;
        if(!$salary) return $this->getErrorMessage('This user doesn\'t have any salary yet.');

        $validator = $this->updatableProperties($request);
        if($validator->fails()) return $this->getErrorMessage($validator->errors());

        $salary->update($this->fitUpdatableInputs($request));
        $calculated_amounts = $this->calculatePayableAmount($salary);

        $gross = $calculated_amounts['gross_salary'];
        $new_pf = (double)$gross * $this->myObject->monthly_deposit_rate * $salary->user->deposit_pf;

        if($new_pf != $salary->provident_fund)
        {
            $salary->update(['provident_fund' => $new_pf]);
            $calculated_amounts = $this->calculatePayableAmount($salary);
        }

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
                'gross_salary' => $calculated_amounts['gross_salary'],
                'total_deduction' => $calculated_amounts['total_deduction'],
                'net_salary' => $calculated_amounts['net_salary'],
            ]
        ];
    }

    private function validateSalary()
    {
        return request()->validate ([
            'user_id' => 'required|string|unique:salaries',
            'basic_salary' => 'required|string',
            'house_rent_allowance' => 'nullable|string',
            'medical_allowance' => 'nullable|string',
            'special_allowance' => 'nullable|string',
            'fuel_allowance' => 'nullable|string',
            'phone_bill_allowance' => 'nullable|string',
            'other_allowance' => 'nullable|string',
            'tax_deduction' => 'nullable|string',
            'other_deduction' => 'nullable|string',
        ]);
    }

    private function updatableProperties(Request $request)
    {
        return Validator::make($request->all(), [
            'basic_salary' => 'string',
            'house_rent_allowance' => 'nullable|string',
            'medical_allowance' => 'nullable|string',
            'special_allowance' => 'nullable|string',
            'fuel_allowance' => 'nullable|string',
            'phone_bill_allowance' => 'nullable|string',
            'other_allowance' => 'nullable|string',
            'tax_deduction' => 'nullable|string',
            'other_deduction' => 'nullable|string',
        ]);
    }

    private function fitUpdatableInputs(Request $request)
    {
        $data = [];

        if($request->filled('basic_salary')) $data['basic_salary'] = $request->input('basic_salary');
        if($request->filled('house_rent_allowance')) $data['house_rent_allowance'] = $request->input('house_rent_allowance');
        if($request->filled('medical_allowance')) $data['medical_allowance'] = $request->input('medical_allowance');
        if($request->filled('special_allowance')) $data['special_allowance'] = $request->input('special_allowance');
        if($request->filled('fuel_allowance')) $data['fuel_allowance'] = $request->input('fuel_allowance');
        if($request->filled('phone_bill_allowance')) $data['phone_bill_allowance'] = $request->input('phone_bill_allowance');
        if($request->filled('other_allowance')) $data['other_allowance'] = $request->input('other_allowance');
        if($request->filled('tax_deduction')) $data['tax_deduction'] = $request->input('tax_deduction');
        if($request->filled('other_deduction')) $data['other_deduction'] = $request->input('other_deduction');

        return $data;
    }
}

























//
