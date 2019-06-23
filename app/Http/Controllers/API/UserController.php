<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\CustomsErrorsTrait;

class UserController extends Controller
{

    use CustomsErrorsTrait;

    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']); // remove register from here and ensure only admin is creating user
    }

    public function login()
    {
        if( Auth::attempt(['email' => request('email'), 'password' => request('password')]) or
            Auth::attempt(['user_name' => request('user_name'), 'password' => request('password')]) )
        {
            $user = Auth::user();
            $success['token'] = $user->createToken(config('app.name'))->accessToken;

            return
            [
                [
                    'status' => 'OK',
                    'full_name' => $user->full_name,
                    'user_name' => $user->user_name,
                    'email' => $user->email,
                    'token' => $success['token'],
                ]
            ];
        }

        else
        {
            //return response()->json(['error'=>'Unauthorised'], 401);
            return $this->getErrorMessage('credentials are not matched.');
        }
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

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string',
            'full_name' => 'required|string|min:3|max:25',
            'user_name' => 'required|string|min:3|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            // 'c_password' => 'required|same:password',
            'date_of_birth' => 'required|date',
            'fathers_name' => 'required|string|min:3|max:25',
            'gender' => 'required|string',
            'marital_status' => 'required|string',
            'nationality' => 'required|string',
            'permanent_address' => 'required|string|min:10|max:300',
            'present_address' => 'required|string|min:10|max:300',
            'passport_number' => 'required|string',
            'phone' => 'required|string',
            'designation_id' => 'required|string',
            'department_id' => 'required|string',
            // 'working_days_id' => 'required|string',      // will be done later
            'joining_date' => 'required|date',
        ]);

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        $user = User::create($this->getProcessedInputs($request, 1));
        $success['token'] = $user->createToken(config('app.name'))->accessToken;

        return
        [
            [
                'status' => 'OK',
                'full_name' => $user->full_name,
                'user_name' => $user->user_name,
                'email' => $user->email,
                'token' => $success['token'],
            ]
        ];
    }

    public function user()
    {
        $user = Auth::user();

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

        $validator = $this->getValidatorArray($request);

        if($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        $user_to_be_updated = User::findOrFail($id);
        $user_to_be_updated->update($this->getProcessedInputs($request, 0));

        return
        [
            [
                'status' => 'OK',
                'message' => 'Info Updated',
            ]
        ];
    }

    private function getProcessedInputs(Request $request, int $regOrUpdt) // 1 for registration, 0 for update.
    {
        $soft_inputs = [];

        if($request->filled('user_name')) $soft_inputs['user_name'] = $request->input('user_name');
        if($request->filled('email')) $soft_inputs['email'] = $request->input('email');
        if($request->filled('full_name')) $soft_inputs['full_name'] = $request->input('full_name');
        if($request->filled('date_of_birth')) $soft_inputs['date_of_birth'] = $request->input('date_of_birth');
        if($request->filled('fathers_name')) $soft_inputs['fathers_name'] = $request->input('fathers_name');
        if($request->filled('gender')) $soft_inputs['gender'] = $request->input('gender');
        if($request->filled('marital_status')) $soft_inputs['marital_status'] = $request->input('marital_status');
        if($request->filled('nationality')) $soft_inputs['nationality'] = $request->input('nationality');
        if($request->filled('permanent_address')) $soft_inputs['permanent_address'] = $request->input('permanent_address');
        if($request->filled('present_address')) $soft_inputs['present_address'] = $request->input('present_address');
        if($request->filled('passport_number')) $soft_inputs['passport_number'] = $request->input('passport_number');
        if($request->filled('phone')) $soft_inputs['phone'] = $request->input('phone');

        if($regOrUpdt == 0 and auth()->user()->isAdmin(auth()->id()) == 'false')
        {
            return $soft_inputs;
        }

        if($request->filled('department_id')) $soft_inputs['department_id'] = $request->input('department_id');
        if($request->filled('designation_id')) $soft_inputs['designation_id'] = $request->input('designation_id');
        if($request->filled('working_days_id')) $soft_inputs['working_days_id'] = 0; // will be done later
        if($request->filled('joining_date')) $soft_inputs['joining_date'] = $request->input('joining_date');

        if($regOrUpdt == 1)
        {
            $soft_inputs['employee_id'] = $request->input('employee_id');
            $soft_inputs['password'] = bcrypt($request->input('password'));
        }

        else
        {
            if($request->filled('status')) $soft_inputs['status'] = $request->input('status');
        }

        return $soft_inputs;
    }

    private function getValidatorArray(Request $request)
    {
        return Validator::make($request->all(), [
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
            'designation_id' => 'string',
            'department_id' => 'string',
            'joining_date' => 'date',
            'status' => 'min:1|max:1',
        ]);
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

    public function index(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        return
        [
            [
                'status' => 'OK',
                'all_users' => User::all(),
            ]
        ];
    }
}
























//
