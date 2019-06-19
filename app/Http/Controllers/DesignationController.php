<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Department;

class DesignationController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        $designations = Designation::all();

        return
        [
            [
                'status' => 'OK',
                'designations' => $designations,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create Designation');

        $validate_attributes = $this->validateDesignation();

        if(Department::findOrFail((int)$validate_attributes['department_id'])->designations->where('designation', $validate_attributes['designation'])->count())
        {
            return $this->getErrorMessage('This department already has this designation');
        }

        $designation = Designation::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'department_name' => $designation->department->department_name,
                'designation' => $designation->designation,
            ]
        ];
    }

    public function show($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view specific Designation');

        $designation_name = Designation::findOrFail($id)->designation;

        return
        [
            [
                'status' => 'OK',
                'designation' => $designation_name,
            ]
        ];
    }

    public function update(Request $request, Designation $designation)
    {
        
    }

    public function destroy(Designation $designation)
    {
        //
    }

    private function validateDesignation()
    {
        return request()->validate([                           // For DATABASE Validation
            'department_id' => 'required|string',
            'designation' => 'required|string',
        ]);
    }
}









//
