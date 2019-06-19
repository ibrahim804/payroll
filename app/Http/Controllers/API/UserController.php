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
        $this->middleware('auth:api')->except(['register', 'login']);
    }

    public function login()
    {
        if(Auth::attempt(['user_name' => request('user_name'), 'password' => request('password')]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken(config('app.name'))->accessToken;

            return
            [
                [
                    'status' => 'OK',
                    'full_name' => $user->full_name,
                    'user_name' => $user->user_name,
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

        auth()->user()->token()->revoke(); // after a long way headache, i found this line :)

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
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:3|max:25',
            'user_name' => 'required|string|min:3|max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            'c_password' => 'required|same:password',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'nationality' => 'required|string',
            'passport_number' => 'required|string',
            'personal_address' => 'required|string|min:10|max:300',
            'city' => 'required|string',
            'phone' => 'required|string',
            'designation_id' => 'required|string',
            'department_id' => 'required|string',
            'salary_id' => 'required|string',
            'working_days_id' => 'required|string',
            'joining_date' => 'required|date',
        ]);

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
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
        $validator = $this->getValidatorArray($request);

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());
        }

        $user = Auth::user();
        $user_to_be_updated = User::findOrFail($id);

        if($user->isAdmin($user->id) == 'true')
        {
            $input = $request->all();
            $user_to_be_updated->update($input);

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Info Updated',
                ]
            ];
        }

        if($user->id == $id)
        {
            $user_to_be_updated->update($this->getSoftInputs($request));

            return
            [
                [
                    'status' => 'OK',
                    'message' => 'Soft inputs updated, but restricted inputs did not.',
                ]
            ];

        }

        return $this->getErrorMessage('Permission denied');
    }

    private function getSoftInputs(Request $request)
    {
        $soft_inputs = [];

        if($request->filled('full_name')) $soft_inputs['full_name'] = $request->input('full_name');
        if($request->filled('email')) $soft_inputs['email'] = $request->input('email');
        if($request->filled('date_of_birth')) $soft_inputs['date_of_birth'] = $request->input('date_of_birth');
        if($request->filled('gender')) $soft_inputs['gender'] = $request->input('gender');
        if($request->filled('nationality')) $soft_inputs['nationality'] = $request->input('nationality');
        if($request->filled('passport_number')) $soft_inputs['passport_number'] = $request->input('passport_number');
        if($request->filled('personal_address')) $soft_inputs['personal_address'] = $request->input('personal_address');
        if($request->filled('city')) $soft_inputs['city'] = $request->input('city');
        if($request->filled('phone')) $soft_inputs['phone'] = $request->input('phone');

        return $soft_inputs;
    }

    private function getValidatorArray(Request $request)
    {
        return Validator::make($request->all(), [
            'full_name' => 'string|min:3|max:25',
            'user_name' => 'string|min:3|max:25|unique:users',
            'email' => 'string|email|max:255|unique:users',
            'date_of_birth' => 'date',
            'gender' => 'string',
            'nationality' => 'string',
            'passport_number' => 'string',
            'photo_path' => 'string',
            'personal_address' => 'string|min:10|max:300',
            'city' => 'string',
            'phone' => 'string',
            'designation_id' => 'string',
            'department_id' => 'string',
            'salary_id' => 'string',
            'working_days_id' => 'string',
            'joining_date' => 'date',
            'status' => 'min:1|max:1'
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
