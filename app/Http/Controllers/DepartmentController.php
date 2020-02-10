<?php

namespace App\Http\Controllers;

use App\Department;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomsErrorsTrait;
use Validator;

class DepartmentController extends Controller
{
    use CustomsErrorsTrait;

    public function __construct()
    {
        $this->middleware('auth:api'); //->except(['register', 'login']);
    }

    public function index()
    {
        $departments = Department::all();

        return
        [
            [
                'status' => 'OK',
                'departments' => $departments,
            ]
        ];
    }

    public function store(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to create Department');

        $validator = $this->validateDepartment($request);

        if ($validator->fails()) return $this->getErrorMessage($validator->errors());

        $department = Department::create($request->all());

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

    public function designations($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view any Department\'s designations');

        $designations = Department::findOrFail($id)->designations;

        return
        [
            [
                'status' => 'OK',
                'designations' => $designations,
            ]
        ];
    }

    public function thisDeptDesgnUser($dept_id, $desgn_id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission');

        $users = Department::findOrFail($dept_id)->users->where('designation_id', $desgn_id);
        $i=0; $infos = [];

        foreach($users as $user) {

            $infos[$i] = new User;
            $infos[$i]->id = $user->id;
            $infos[$i]->full_name = $user->full_name;
            $i++;
        }

        return
        [
            [
                'status' => 'OK',
                'users' => $infos,
            ]
        ];
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to update Dept Info.');

        $department = Department::findOrFail($id);
        $validator = $this->validateDepartment($request);

        if ($validator->fails()) return $this->getErrorMessage($validator->errors());

        $department_old_name = $department->department_name;
        $department->update($request->all());

        return
        [
            [
                'status' => 'OK',
                'department_old_name' => $department_old_name,
                'department_new_name' => $department->department_name,
            ]
        ];
    }

    // public function destroy($id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to destroy Department');
    //
    //     Department::findOrFail($id)->delete();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'message' => 'Requested department deleted successfully',
    //         ]
    //     ];
    // }
    //
    // public function restore($id)
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to restore Department');
    //
    //     Department::onlyTrashed()->where('id', $id)->restore();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'message' => 'Requested department is restored successfully.',
    //         ]
    //     ];
    // }
    //
    // public function trashedIndex()
    // {
    //     if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to view deleted Departments');
    //
    //     $departments = Department::onlyTrashed()->get();
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'departments' => $departments,
    //         ]
    //     ];
    // }

    public function users($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('You don\'t have permission to show users');

        $users = Department::findOrFail($id)->users;

        return
        [
            [
                'status' => 'OK',
                'users' => $users,
            ]
        ];
    }

    private function validateDepartment(Request $request)
    {
        return Validator::make($request->all(), [
            'department_name' => 'required|string|unique:departments',
        ]);
    }
}
