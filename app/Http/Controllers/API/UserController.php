<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomsErrorsTrait;

class UserController extends Controller
{

    use CustomsErrorsTrait;

    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    public function index(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        $users = User::all();
        $i = 0; $infos = [];

        foreach($users as $user) {

            $infos[$i] = new User;

            $infos[$i]->id = $user->id;
            $infos[$i]->full_name = $user->full_name;
            $infos[$i]->salary = ($user->salary) ? $this->calculateNetSalary($user->salary) : 'N/A';
            $infos[$i]->department = ($user->department) ? $user->department->department_name : 'N/A';
            $infos[$i]->designation = ($user->designation) ? $user->designation->designation : 'N/A';
            $infos[$i]->casual_leave = $user->leave_counts->where('leave_category_id', 1)->first()->leave_left;
            $infos[$i]->sick_leave = $user->leave_counts->where('leave_category_id', 2)->first()->leave_left;

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

    private function calculateNetSalary($salary)
    {
        return $salary['basic_salary']
            +  $salary['house_rent_allowance'] + $salary['medical_allowance']
            +  $salary['special_allowance'] + $salary['fuel_allowance']
            +  $salary['phone_bill_allowance'] + $salary['other_allowance']
            -  $salary['tax_deduction'] -  $salary['provident_fund'] - $salary['other_deduction'];
    }

    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken(config('app.name'))->accessToken;

            return
            [
                [
                    'status' => 'OK',
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'token' => $success['token'],
                ]
            ];
        }

        return $this->getErrorMessage('credentials are not matched.');

    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();

        return
        [
            [
                'status' => 'OK',
                'message' => 'Logged Out Successfully',
            ]
        ];
    }

    public function register(Request $request)
    {
        // if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Only admin can create user');

        $validator = $this->validateUser($request->all());

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        $user = User::create($this->getProcessedInputsWhileCreatingUser($request));
        $success['token'] = $user->createToken(config('app.name'))->accessToken;

        return
        [
            [
                'status' => 'OK',
                'full_name' => $user->full_name,
                'email' => $user->email,
                'token' => $success['token'],
            ]
        ];
    }

    public function user($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false' and auth()->id() != $id)
        {
            return $this->getErrorMessage('You don\'t have permission to view any user info');
        }

        $user = User::findOrFail($id);

        return
        [
            [
                'status' => 'OK',
                'description' => $user,
            ]
        ];
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if($user->isAdmin($user->id) == 'false' and $user->id != $id) return $this->getErrorMessage('Permission denied');

        $validate_attributes = request()->validate([
            'full_name' => 'string|min:3|max:25',
            'user_name' => 'string|min:3|max:25|unique:users',
            'email' => 'string|email|max:255|unique:users',
            'date_of_birth' => 'date',
            'fathers_name' => 'string|min:3|max:25',
            'gender' => 'string',
            'marital_status' => 'string',
            'nationality' => 'string',
            'permanent_address' => 'string|min:10|max:300',
            'present_address' => 'string|min:10|max:300',
            'passport_number' => 'string',
            'phone' => 'string',
        ]);

        $user_to_be_updated = User::findOrFail($id);
        $user_to_be_updated->update($validate_attributes);

        if($user->isAdmin($user->id) == 'true')
        {
            $extra_attributes = request()->validate([
                'employee_id' => 'string',
                'designation_id' => 'string',
                'department_id' => 'string',
                'salary_id' => 'string',
                'working_day_id' => 'string',
                'joining_date' => 'date',
                'status' => 'min:1|max:1',
            ]);

            $user_to_be_updated->update($extra_attributes);
        }

        return
        [
            [
                'status' => 'OK',
                'message' => 'Info Updated',
            ]
        ];
    }

    private function getProcessedInputsWhileCreatingUser(Request $request)
    {
        $inputs = [];

        $inputs['full_name'] = $request->input('full_name');
        $inputs['email'] = $request->input('email');
        $inputs['password'] = bcrypt($request->input('password'));
        $inputs['gender'] = $request->input('gender');
        $inputs['phone'] = $request->input('phone');
        $inputs['joining_date'] = $request->input('joining_date');

        if($request->filled('employee_id')) $inputs['employee_id'] = $request->input('employee_id');
        if($request->filled('user_name')) $inputs['user_name'] = $request->input('user_name');
        if($request->filled('date_of_birth')) $inputs['date_of_birth'] = $request->input('date_of_birth');
        if($request->filled('fathers_name')) $inputs['fathers_name'] = $request->input('fathers_name');
        if($request->filled('marital_status')) $inputs['marital_status'] = $request->input('marital_status');
        if($request->filled('nationality')) $inputs['nationality'] = $request->input('nationality');
        if($request->filled('permanent_address')) $inputs['permanent_address'] = $request->input('permanent_address');
        if($request->filled('present_address')) $inputs['present_address'] = $request->input('present_address');
        if($request->filled('passport_number')) $inputs['passport_number'] = $request->input('passport_number');
        if($request->filled('department_id')) $inputs['department_id'] = $request->input('department_id');
        if($request->filled('designation_id')) $inputs['designation_id'] = $request->input('designation_id');

        return $inputs;
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        if(Auth::guard('web')->attempt(['id' => auth()->id(), 'password' => $request->input('current_password')]))
        {
            $new_password = bcrypt($request->input('new_password'));
            auth()->user()->update(['password' => $new_password]);

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Password has been changed successfully',
                ]
            ];
        }

        return $this->getErrorMessage('Current password is not correct.');
    }

    public function forgot_password(Request $request)
    {
        return 'hello';
    }

    public function delete(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        User::findOrFail($id)->delete();

        return
        [
            [
                'status' => 'OK',
                'message' => 'User deleted successfully',
            ]
        ];
    }

    public function restore(Request $request, $id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied for normal user');

        User::onlyTrashed()->where('id', $id)->restore();

        return
        [
            [
                'status' => 'OK',
                'message' => 'User restored successfully.',
            ]
        ];
    }
}
























//
