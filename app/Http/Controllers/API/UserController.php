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

            // return response()->json(['success' => $success], $this->successStatus);

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

    public function logout(Request $request) {

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
            'user_name' => 'required|string|min:3:max:25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30',
            'c_password' => 'required|same:password',
            'date_of_birth' => 'required|string',
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
            'joining_date' => 'required|string',
        ]);

        if ($validator->fails())
        {
            //return response()->json(['error'=>$validator->errors()], 401);
            return $this->getErrorMessage($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] = $user->createToken(config('app.name'))->accessToken;
        $success['name'] = $user->name;

        // return response()->json(['success'=>$success], $this->successStatus);

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

        //return response()->json(['success' => $user], $this->successStatus);

        return
        [
            [
                'status' => 'OK',
                'description' => $user,
            ]
        ];
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    //
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|min:3|max:25',
    //     ]);
    //
    //     if ($validator->fails())
    //     {
    //         return $this->getErrorMessage($validator->errors());
    //     }
    //
    //     $user->update(['name' => $request->input('name')]);
    //
    //     return
    //     [
    //         [
    //             'status' => 'OK',
    //             'updated_name' => $user->name,
    //         ]
    //     ];
    // }
    //
    // public function change_password(Request $request)
    // {
    //
    //     if(Auth::user()->isFbUser)
    //     {
    //         return $this->getErrorMessage('Facebook users are not allowed to change their password.');
    //     }
    //
    //     $validator = Validator::make($request->all(), [
    //         'current_password' => 'required',
    //         'new_password' => 'required|string|min:6|max:30',
    //         'confirm_password' => 'required|same:new_password',
    //     ]);
    //
    //     if ($validator->fails())
    //     {
    //         return $this->getErrorMessage($validator->errors());
    //     }
    //
    //     if(Auth::guard('web')->attempt(['id' => auth()->id(), 'password' => $request->input('current_password')]))
    //     {
    //         $new_password = bcrypt($request->input('new_password'));
    //         auth()->user()->update(['password' => $new_password]);
    //
    //         return
    //         [
    //             [
    //                 'status' => 'OK',
    //                 'message' => 'Password has been changed successfully',
    //             ]
    //         ];
    //     }
    //
    //     return $this->getErrorMessage('Current password is not correct.');
    // }
    //
    // public function forgot_password(Request $request)
    // {
    //     if(Auth::user()->isFbUser)
    //     {
    //         return $this->getErrorMessage('Facebook users are not allowed to recover their password.');
    //     }
    // }

    public function delete(Request $request){
        
    }
}
