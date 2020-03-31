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
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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

    public function designations($id)
    {
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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
        if(auth()->user()->role->type != 'admin') return $this->getErrorMessage('Permission Denied');

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

    private function validateDepartment(Request $request)
    {
        return Validator::make($request->all(), [
            'department_name' => 'required|string|unique:departments',
        ]);
    }
}
