<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Designation;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CustomsErrorsTrait;
use App\Http\Controllers\SharedTrait;
use Validator;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerification;
use Carbon\Carbon;
use App\MyErrorObject;
use App\LeaveCount;

class UserController extends Controller
{

    /*
        CustomsErrorsTrait is a trait which contains some methods. Every Controller that uses CustomsErrorsTrait can access all it's methods.
         To access some methods globally, we generally create a trait and write function there.
    */

    use CustomsErrorsTrait, SharedTrait;

    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'forgot_password', 'verifyVerificationCode', 'setNewPasswordAfterUserVerification']);

        /* To allow some specific methods instead, we use "only".
            For example: $this->middleware('auth:api')->only(['index', logout]);
            Both only and except can't be used within a same Controller.
        */
    }

    /*
        returns all users' id, full_name, payable salary, company name, department, designation, casual_leave left, sick_leave left, photo_path,
    */

    public function index(Request $request)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        $users = User::all();               // fetches all users
        $i = 0; $infos = [];

        foreach($users as $user) {

            $infos[$i] = new User;

            if($user->isAdmin($user->id) == 'true') continue;

            $infos[$i]->id = $user->id;
            $infos[$i]->full_name = $user->full_name;
            $infos[$i]->email = $user->email;
            $infos[$i]->phone = $user->phone;
            $infos[$i]->salary = ($user->salary) ? $this->calculatePayableAmount($user->salary)['net_salary'] : 'N/A';
            $infos[$i]->gross_salary = ($user->salary) ? $this->calculatePayableAmount($user->salary)['gross_salary'] : 'N/A';
            $infos[$i]->total_deduction = ($user->salary) ? $this->calculatePayableAmount($user->salary)['total_deduction'] : 'N/A';
            $infos[$i]->company = ($user->company) ? $user->company->name : 'N/A';
            $infos[$i]->department = ($user->department) ? $user->department->department_name : 'N/A';
            $infos[$i]->designation = ($user->designation) ? $user->designation->designation : 'N/A';

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

    /*
        Input: email and password
        returns full_name, email and token.
    */

    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $user = Auth::user();   // user is now logged in
            $success['token'] = $user->createToken(config('app.name'))->accessToken;    // create a token

            if($user->verification_code) $user->update(['verification_code' => NULL]);

            return
            [
                [
                    'status' => 'OK',
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'token' => $success['token'],
                    'role' => ($user->isAdmin($user->id) == 'true') ? 'admin' : 'user',
                ]
            ];
        }

        /*
            getErrorMessage is a method under CustomsErrorsTrait. As, this Controller uses CustomsErrorsTrait, it get's access of this method.
        */

        return $this->getErrorMessage('credentials are not matched.');

    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();      // The token is now no longer available.

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
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Only admin can create user');

        $validator = $this->validateUser($request->all());          // validateUser is a method of SharedTrait. Please check

        if ($validator->fails())
        {
            return $this->getErrorMessage($validator->errors());    // getErrorMessage is a method of CustomsErrorsTrait
        }

        /*
            About user creation:
                All parameters that admin fills out are not required. It must be checked whether not-required fields are filled out or not.
        */

        $user = User::create($this->getProcessedInputsWhileCreatingUser($request));
        return redirect('api/leave-count/user/'.$user->id);       // REDIRECTS TO LEAVE COUNT STORE METHOD
    }

    public function get_me()
    {
        $user = auth()->user();

        $pf_counts = $user->provident_funds->count();
        $pf_amount = ($pf_counts > 0) ? $user->provident_funds()->latest()->first()->closing_balance : 0;
        $lh_counts = $user->loan_histories->count();
        $lh_loan = ($lh_counts > 0) ? $user->loan_histories()->latest()->first()->current_loan_amount : 0;

        return
        [
            [
                'status' => 'OK',
                'description' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'present_address' => ($user->present_address)? $user->present_address: 'N/A',
                    'department_name' => ($user->department) ? $user->department->department_name : 'N/A',
                    'designation' => ($user->designation) ? $user->designation->designation : 'N/A',
                    'joining_date' => $user->joining_date,
                    'net_salary' => ($user->salary) ? $this->calculatePayableAmount($user->salary)['net_salary']: 'N/A',
                    'provident_fund' => $pf_amount,
                    'on_loan' => $lh_loan,
                ]
            ]
        ];
    }

    public function user_dept_desg($id)
    {
        if(auth()->user()->isAdmin(auth()->id()) == 'false') return $this->getErrorMessage('Permission denied');

        $user = User::find($id);

        return
        [
            [
                'status' => 'OK',
                'full_name' => $user->full_name,
                'department_id' => $user->department_id,
                'department_name' => $user->department->department_name,
                'designation_id' => $user->designation_id,
                'designation' => $user->designation->designation,
            ]
        ];

    }

    /*
        Specific user can update her/his info. But admin can anyone's.
    */

    public function update(Request $request)
    {
        $user = Auth::user();

        $validate_attributes = request()->validate([
            'full_name' => 'nullable|string|min:3|max:25',
            'email' => 'nullable|string|email|max:255|unique:users',
            'date_of_birth' => 'nullable|date',
            'fathers_name' => 'nullable|string|min:3|max:25',
            'gender' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'nationality' => 'nullable|string',
            'permanent_address' => 'nullable|string|min:10|max:300',
            'present_address' => 'nullable|string|min:10|max:300',
            'passport_number' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $inputs = [];

        if($validate_attributes['full_name']) $inputs['full_name'] = $request->input('full_name');
        if($validate_attributes['email']) $inputs['email'] = $request->input('email');
        if($validate_attributes['gender']) $inputs['gender'] = $request->input('gender');
        if($validate_attributes['phone']) $inputs['phone'] = $request->input('phone');
        if($validate_attributes['date_of_birth']) $inputs['date_of_birth'] = $request->input('date_of_birth');
        if($validate_attributes['fathers_name']) $inputs['fathers_name'] = $request->input('fathers_name');
        if($validate_attributes['marital_status']) $inputs['marital_status'] = $request->input('marital_status');
        if($validate_attributes['nationality']) $inputs['nationality'] = $request->input('nationality');
        if($validate_attributes['permanent_address']) $inputs['permanent_address'] = $request->input('permanent_address');
        if($validate_attributes['present_address']) $inputs['present_address'] = $request->input('present_address');
        if($validate_attributes['passport_number']) $inputs['passport_number'] = $request->input('passport_number');

        $user->update($inputs);

        return
        [
            [
                'status' => 'OK',
                'inputs' => $inputs,
                'user' => $user,
            ]
        ];
    }

    /*
        For not-required parameters: If any parameter is filled out, it will be stored.
    */

    private function getProcessedInputsWhileCreatingUser(Request $request)
    {
        $inputs = [];

        /*
            We can insert values in $inputs array in the following way,

            $inputs = $request->all();

            But the problem is, some unexpected parameters can be passed. But out list is specific.
        */

        $inputs['full_name'] = $request->input('full_name');
        $inputs['email'] = $request->input('email');
        $inputs['password'] = bcrypt($request->input('password'));
        $inputs['gender'] = $request->input('gender');
        $inputs['phone'] = $request->input('phone');
        $inputs['joining_date'] = $request->input('joining_date');
        $inputs['role_id'] = $request->input('role_id');
        $inputs['id_of_leader'] = $request->input('id_of_leader');

        if($request->filled('employee_id')) $inputs['employee_id'] = $request->input('employee_id');
        if($request->filled('user_name')) $inputs['user_name'] = $request->input('user_name');
        if($request->filled('date_of_birth')) $inputs['date_of_birth'] = $request->input('date_of_birth');
        if($request->filled('fathers_name')) $inputs['fathers_name'] = $request->input('fathers_name');
        if($request->filled('marital_status')) $inputs['marital_status'] = $request->input('marital_status');
        if($request->filled('nationality')) $inputs['nationality'] = $request->input('nationality');
        if($request->filled('permanent_address')) $inputs['permanent_address'] = $request->input('permanent_address');
        if($request->filled('present_address')) $inputs['present_address'] = $request->input('present_address');
        if($request->filled('passport_number')) $inputs['passport_number'] = $request->input('passport_number');
        if($request->filled('company_id')) $inputs['company_id'] = $request->input('company_id');
        if($request->filled('department_id')) $inputs['department_id'] = $request->input('department_id');
        if($request->filled('designation_id')) $inputs['designation_id'] = $request->input('designation_id');
        if($request->filled('deposit_pf')) $inputs['deposit_pf'] = $request->input('deposit_pf');

        return $inputs;
    }

    /*
        The following route will be called when user knows her/his password but wants to update
    */

    public function change_password()
    {
        $validate_attributes = $this->validatePassword();

        if(Auth::guard('web')->attempt(['id' => auth()->id(), 'password' => $validate_attributes['current_password']]))
        {
            $new_password = bcrypt($validate_attributes['new_password']);        // new_password should be hashed beforing storing
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

    /*
        Hits when user forgets her/his password. User must provide her/his email to get mail.
        1. System sends a verification code to user's mail.
        2. Store that verification code to user's database for later verification
    */

    public function forgot_password(Request $request)
    {
        $email = $request->input('email');
        if(!$email) return $this->getErrorMessage('Please, enter your email');

        $user = User::where('email', $email)->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');

        $verification_code = mt_rand(100000, 999999);           // generate a 6 digit code

        Mail::to($email)->send(
            new UserVerification($verification_code, $user->full_name, ($user->company) ? $user->company->name : '')
        );

        $user->update(['verification_code' => $verification_code]);     // verification_code is stored in user database for verify this user later

        return
        [
            [
                'status' => 'OK',
                'message' => 'A verification code is sent to your email, Check your inbox now.',
            ]
        ];
    }

    /*
        Following route will be called after receiving verification code
        Input: take email for querying verification code from database

        Then user submit code here what s/he received to set new password. The field for verification code of database can be,
        1. NULL (reasons are: code is not sent successfully through mail, or code gets expired already. Note that, code gets expired when it is one minute old)
        2. 6 digit number (Code is sent to mail, but not verified yet.)
        3. 1 (When user is already verified)

        Output: Ensures that user is now allowed to set a new password
    */

    public function verifyVerificationCode(Request $request)
    {
        $email = $request->input('email');
        $verification_code = $request->input('verification_code');

        if(!$email or !$verification_code)
        {
          return $this->getErrorMessage('Email and Verification code must be given');
        }

        $user = User::where('email', $email)->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');

        // if($user->verification_code == 1)
        // {
        //     return
        //     [
        //         [
        //             'status' => 'OK',
        //             'message' => 'Verification code already verified, you can set your password now.',
        //         ]
        //     ];
        // }

        $user_updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at);          // when verification code is created.
        $current_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
        $ageOfVerificationCode = $user_updated_at->diffInSeconds($current_time);                // get the age of verification code

        if($ageOfVerificationCode >= 120) $user->update(['verification_code' => NULL]);          // verification code get expired after one minute

        if(! $user->verification_code) return $this->getErrorMessage('Either you didn\'t send forgot password request, or your code expired');
        if($verification_code != $user->verification_code) return $this->getErrorMessage('Verification code doesn\'t match');

        $user->update(['verification_code' => 1]);                                              // OK, user is not verified and allowed to set new password

        return
        [
            [
                'status' => 'OK',
                'message' => 'Verification code verified successfully.',
            ]
        ];

    }

    public function setNewPasswordAfterUserVerification(Request $request)
    {
        $validate_attributes = request()->validate([            // these validations are not working for being out of auth:api. I don't know why. Check it later.
            'email' => 'required|string',
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = User::where('email', $validate_attributes['email'])->first();

        if(! $user) return $this->getErrorMessage('User with this email doesn\'t exist');
        if($user->verification_code != 1) return $this->getErrorMessage('Please verify your verification code firstly to set new password'); // No permission if code is not verified yet.

        $user->update([
            'password' => bcrypt($validate_attributes['new_password']),             // password is hashed
            'verification_code' => NULL,
        ]);

        return
        [
            [
                'status' => 'OK',
                'message' => 'User password reset successfully.',
            ]
        ];
    }

    private function validatePassword() {
        return request()->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);
    }
}











//
