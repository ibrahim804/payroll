<?php

namespace App\Http\Controllers;

use App\Salary;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;

class SalaryController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view all Salaries');

        $salaries = Salary::all();

        return
        [
            [
                'status' => 'OK',
                'salaries' => $salaries,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create Salary');

        $validator = $this->validateSalary($request);

        if($validator->fails()) return $this->getErrorMessage($validator->errors());

        $salary = Salary::create($request->all());

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
            ]
        ];
    }

    public function show($user_id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view his/her salary');

        $salary = User::findOrFail($user_id)->salary;

        if(!$salary) return $this->getErrorMessage('This use doesn\'t have any salary yet.');

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
            ]
        ];
    }

    public function update(Request $request, $user_id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Salary Information');

        $salary = User::findOrFail($user_id)->salary;
        if(!$salary) return $this->getErrorMessage('This user doesn\'t have any salary yet.');

        $validator = $this->updatableProperties($request);
        if($validator->fails()) return $this->getErrorMessage($validator->errors());

        $salary->update($this->fitUpdatableInputs($request));

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
            ]
        ];
    }

    private function validateSalary(Request $request)
    {
        return Validator::make($request->all(), [
            'user_id' => 'required|string|unique:salaries',
            'basic_salary' => 'required|string',
            'house_rent_allowance' => 'required|string',
            'medical_allowance' => 'required|string',
            'special_allowance' => 'required|string',
            'fuel_allowance' => 'required|string',
            'phone_bill_allowance' => 'required|string',
            'other_allowance' => 'required|string',
            'tax_deduction' => 'required|string',
            'provident_fund' => 'required|string',
            'other_deduction' => 'required|string',
        ]);
    }

    private function updatableProperties(Request $request)
    {
        return Validator::make($request->all(), [
            'basic_salary' => 'string',
            'house_rent_allowance' => 'string',
            'medical_allowance' => 'string',
            'special_allowance' => 'string',
            'fuel_allowance' => 'string',
            'phone_bill_allowance' => 'string',
            'other_allowance' => 'string',
            'tax_deduction' => 'string',
            'provident_fund' => 'string',
            'other_deduction' => 'string',
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
        if($request->filled('provident_fund')) $data['provident_fund'] = $request->input('provident_fund');
        if($request->filled('other_deduction')) $data['other_deduction'] = $request->input('other_deduction');

        return $data;
    }
}

























//
