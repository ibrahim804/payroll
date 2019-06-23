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

        if ($validator->fails()) return $this->getErrorMessage($validator->errors());

        $salary = Salary::create($request->all());

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
            ]
        ];
    }

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view his/her salary');

        $salary = User::findOrFail($id)->salary;

        if(!$salary) return $this->getErrorMessage('This use doesn\'t have any salary yet.');

        return
        [
            [
                'status' => 'OK',
                'salary' => $salary,
            ]
        ];
    }

    public function update(Request $request, Salary $salary)
    {
        //
    }

    public function destroy(Salary $salary)
    {
        //
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
}

























//
