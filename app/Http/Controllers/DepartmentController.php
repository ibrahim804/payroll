<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;

class DepartmentController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        $department = Department::all();

        return
        [
            [
                'status' => 'OK',
                'departments' => $department,
            ]
        ];
    }

    public function store()
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create Department');

        $validate_attributes = $this->validateDepartment();

        if(Department::where('department_name', $validate_attributes['department_name'])->count())
            return $this->getErrorMessage('This department name already exits.');

        $department = Department::create($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'department_name' => $department,
            ]
        ];
    }

    public function show($id)
    {
        $department_name = Department::findOrFail($id)->department_name;

        return
        [
            [
                'status' => 'OK',
                'department_name' => $department_name,
            ]
        ];
    }

    public function update($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Dept Info.');

        $department = Department::findOrFail($id);
        $department_old_name = $department->department_name;
        $validate_attributes = $this->validateDepartment();
        $department->update($validate_attributes);

        return
        [
            [
                'status' => 'OK',
                'department_old_name' => $department_old_name,
                'department_new_name' => $department->department_name,
            ]
        ];
    }

    public function destroy($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to destroy Department');

        Department::findOrFail($id)->delete();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Requested department deleted successfully',
            ]
        ];
    }

    public function restore($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to destroy Department');

        Department::onlyTrashed()->where('id', $id)->restore();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Requested department is restored successfully.',
            ]
        ];
    }

    public function trashedIndex()
    {
        $department = Department::onlyTrashed()->get();

        return
        [
            [
                'status' => 'OK',
                'departments' => $department,
            ]
        ];
    }

    private function validateDepartment()
    {
        return request()->validate([                           // For DATABASE Validation
            'department_name' => 'required|string',
        ]);
    }
}
