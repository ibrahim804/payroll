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

    public function store()
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

    public function update(Request $request, $id) // designation id
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Designation');

        $designation = Designation::findOrFail($id);
        $validate_attributes = $request->validate(['designation' => 'required|string']);

        if($designation->department->designations->where('designation', $validate_attributes['designation'])->count())
        {
            return $this->getErrorMessage('Requested designation name already exists.');
        }

        $designation_old_name = $designation->designation;
        $designation->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'designation_old_name' => $designation_old_name,
                'designation_new_name' => $designation->designation,
            ]
        ];
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
